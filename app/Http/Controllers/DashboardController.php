<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\TahunAnggaran;
use App\Models\BuktiFile;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tahunAktif = TahunAnggaran::getTahunAktif();

        if (!$tahunAktif) {
            // Jika belum ada tahun aktif, tampilkan dashboard kosong dengan pesan
            return view('dashboard', [
                'tahunAktif' => null,
                'stats' => [
                    'total_kegiatan' => 0,
                    'kegiatan_disetujui' => 0,
                    'kegiatan_belum_disetujui' => 0,
                    'total_pagu' => 0,
                    'total_realisasi' => 0,
                    'total_bukti' => 0,
                    'persentase_realisasi' => 0,
                ],
                'chartData' => collect(),
                'kegiatanTerbaru' => collect(),
                'realisasiTerbaru' => collect(),
                'roleSpecificData' => [],
                'quickActions' => [],
            ]);
        }

        // Statistik dasar
        $stats = [
            'total_kegiatan' => Kegiatan::byTahun($tahunAktif->id)->count(),
            'kegiatan_disetujui' => Kegiatan::byTahun($tahunAktif->id)->byStatus('disetujui')->count(),
            'kegiatan_belum_disetujui' => Kegiatan::byTahun($tahunAktif->id)
                ->whereIn('status', ['draft', 'verifikasi'])->count(),
            'total_pagu' => Kegiatan::byTahun($tahunAktif->id)->sum('pagu_anggaran'),
            'total_realisasi' => Realisasi::whereHas('kegiatan', function($query) use ($tahunAktif) {
                $query->where('tahun_id', $tahunAktif->id);
            })->sum('jumlah_realisasi'),
            'total_bukti' => BuktiFile::whereHas('realisasi.kegiatan', function($query) use ($tahunAktif) {
                $query->where('tahun_id', $tahunAktif->id);
            })->count(),
        ];

        // Persentase realisasi
        $stats['persentase_realisasi'] = $stats['total_pagu'] > 0
            ? ($stats['total_realisasi'] / $stats['total_pagu']) * 100
            : 0;

        // Data untuk chart - Anggaran vs Realisasi per bidang
        $chartData = Kegiatan::byTahun($tahunAktif->id)
            ->select('bidang')
            ->selectRaw('SUM(pagu_anggaran) as total_pagu')
            ->selectRaw('(SELECT SUM(jumlah_realisasi) FROM realisasi WHERE kegiatan_id = kegiatan.id) as total_realisasi')
            ->groupBy('bidang')
            ->get();

        // Kegiatan terbaru
        $kegiatanTerbaru = Kegiatan::byTahun($tahunAktif->id)
            ->with(['pembuatKegiatan', 'tahunAnggaran'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Realisasi terbaru
        $realisasiTerbaru = Realisasi::whereHas('kegiatan', function($query) use ($tahunAktif) {
                $query->where('tahun_id', $tahunAktif->id);
            })
            ->with(['kegiatan', 'pembuat'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Role-specific data dan quick actions
        $roleSpecificData = $this->getRoleSpecificData($user, $tahunAktif);
        $quickActions = $this->getQuickActions($user);

        return view('dashboard', compact(
            'tahunAktif',
            'stats',
            'chartData',
            'kegiatanTerbaru',
            'realisasiTerbaru',
            'roleSpecificData',
            'quickActions'
        ));
    }

    /**
     * Get role-specific data for dashboard
     */
    private function getRoleSpecificData($user, $tahunAktif)
    {
        $data = [];

        if ($user->hasRole('admin')) {
            $data = [
                'title' => 'Panel Administrator',
                'total_users' => \App\Models\User::count(),
                'total_tahun_anggaran' => TahunAnggaran::count(),
                'recent_activities' => \App\Models\LogAktivitas::with('user')
                    ->latest()
                    ->limit(5)
                    ->get(),
                'system_stats' => [
                    'total_files' => \App\Models\BuktiFile::count(),
                    'total_logs' => \App\Models\LogAktivitas::count(),
                ]
            ];
        } elseif ($user->hasRole('kepala-desa')) {
            $pendingApprovals = Kegiatan::byTahun($tahunAktif->id)
                ->byStatus('verifikasi')
                ->count();

            $data = [
                'title' => 'Panel Kepala Desa',
                'pending_approvals' => $pendingApprovals,
                'approved_this_month' => Kegiatan::byTahun($tahunAktif->id)
                    ->byStatus('disetujui')
                    ->whereMonth('updated_at', now()->month)
                    ->count(),
                'pending_kegiatan' => Kegiatan::byTahun($tahunAktif->id)
                    ->byStatus('verifikasi')
                    ->with('pembuatKegiatan')
                    ->latest()
                    ->limit(5)
                    ->get(),
                'budget_summary' => [
                    'total_approved' => Kegiatan::byTahun($tahunAktif->id)
                        ->byStatus('disetujui')
                        ->sum('pagu_anggaran'),
                ]
            ];
        } elseif ($user->hasRole('sekretaris')) {
            $pendingVerifications = Kegiatan::byTahun($tahunAktif->id)
                ->byStatus('draft')
                ->count();

            $data = [
                'title' => 'Panel Sekretaris',
                'pending_verifications' => $pendingVerifications,
                'verified_this_month' => Kegiatan::byTahun($tahunAktif->id)
                    ->whereIn('status', ['verifikasi', 'disetujui'])
                    ->whereMonth('updated_at', now()->month)
                    ->count(),
                'pending_kegiatan' => Kegiatan::byTahun($tahunAktif->id)
                    ->byStatus('draft')
                    ->with('pembuatKegiatan')
                    ->latest()
                    ->limit(5)
                    ->get(),
                'verification_stats' => [
                    'total_draft' => Kegiatan::byTahun($tahunAktif->id)
                        ->byStatus('draft')
                        ->count(),
                ]
            ];
        } elseif ($user->hasRole('bendahara')) {
            $totalRealisasi = Realisasi::whereHas('kegiatan', function($q) use ($tahunAktif) {
                $q->where('tahun_id', $tahunAktif->id);
            })->sum('jumlah_realisasi');

            $data = [
                'title' => 'Panel Bendahara',
                'total_realisasi' => $totalRealisasi,
                'realisasi_this_month' => Realisasi::whereHas('kegiatan', function($q) use ($tahunAktif) {
                    $q->where('tahun_id', $tahunAktif->id);
                })
                ->whereMonth('created_at', now()->month)
                ->sum('jumlah_realisasi'),
                'recent_realisasi' => Realisasi::whereHas('kegiatan', function($q) use ($tahunAktif) {
                    $q->where('tahun_id', $tahunAktif->id);
                })
                ->with(['kegiatan'])
                ->latest()
                ->limit(5)
                ->get(),
                'budget_utilization' => [
                    'approved_budget' => Kegiatan::byTahun($tahunAktif->id)
                        ->byStatus('disetujui')
                        ->sum('pagu_anggaran'),
                    'remaining_budget' => Kegiatan::byTahun($tahunAktif->id)
                        ->byStatus('disetujui')
                        ->sum('pagu_anggaran') - $totalRealisasi,
                ]
            ];
        } elseif ($user->hasRole('operator')) {
            $myKegiatan = Kegiatan::byTahun($tahunAktif->id)
                ->where('dibuat_oleh', $user->id);

            $data = [
                'title' => 'Panel Operator',
                'my_kegiatan_total' => $myKegiatan->count(),
                'my_kegiatan_approved' => $myKegiatan->byStatus('disetujui')->count(),
                'my_kegiatan_draft' => $myKegiatan->byStatus('draft')->count(),
                'my_recent_kegiatan' => $myKegiatan
                    ->with('tahunAnggaran')
                    ->latest()
                    ->limit(5)
                    ->get(),
                'my_stats' => [
                    'total_budget_proposed' => $myKegiatan->sum('pagu_anggaran'),
                    'approved_budget' => $myKegiatan->byStatus('disetujui')->sum('pagu_anggaran'),
                ]
            ];
        } elseif ($user->hasRole('auditor')) {
            $data = [
                'title' => 'Panel Auditor',
                'total_activities' => \App\Models\LogAktivitas::count(),
                'activities_this_month' => \App\Models\LogAktivitas::whereMonth('created_at', now()->month)->count(),
                'recent_logs' => \App\Models\LogAktivitas::with('user')
                    ->latest()
                    ->limit(5)
                    ->get(),
                'audit_summary' => [
                    'total_kegiatan' => Kegiatan::byTahun($tahunAktif->id)->count(),
                    'total_realisasi' => Realisasi::whereHas('kegiatan', function($q) use ($tahunAktif) {
                        $q->where('tahun_id', $tahunAktif->id);
                    })->count(),
                    'total_files' => \App\Models\BuktiFile::whereHas('realisasi.kegiatan', function($q) use ($tahunAktif) {
                        $q->where('tahun_id', $tahunAktif->id);
                    })->count(),
                ]
            ];
        }

        return $data;
    }

    /**
     * Get quick actions based on user role
     */
    private function getQuickActions($user)
    {
        $actions = [];

        if ($user->hasRole('admin')) {
            $actions = [
                [
                    'title' => 'Kelola User',
                    'description' => 'Tambah atau edit user sistem',
                    'url' => route('admin.users.index'),
                    'icon' => 'plus',
                    'color' => 'blue'
                ],
                [
                    'title' => 'Tahun Anggaran',
                    'description' => 'Kelola tahun anggaran',
                    'url' => route('admin.tahun-anggaran.index'),
                    'icon' => 'plus',
                    'color' => 'green'
                ],
                [
                    'title' => 'Log Aktivitas',
                    'description' => 'Lihat log sistem',
                    'url' => route('log.index'),
                    'icon' => 'eye',
                    'color' => 'gray'
                ]
            ];
        } elseif ($user->hasRole('kepala-desa')) {
            $actions = [
                [
                    'title' => 'Persetujuan Pending',
                    'description' => 'Lihat kegiatan yang perlu disetujui',
                    'url' => route('kegiatan.index', ['status' => 'verifikasi']),
                    'icon' => 'check',
                    'color' => 'green'
                ],
                [
                    'title' => 'Laporan Anggaran',
                    'description' => 'Lihat laporan keuangan',
                    'url' => route('laporan.show', ['type' => 'keuangan']),
                    'icon' => 'eye',
                    'color' => 'blue'
                ]
            ];
        } elseif ($user->hasRole('sekretaris')) {
            $actions = [
                [
                    'title' => 'Verifikasi Kegiatan',
                    'description' => 'Verifikasi kegiatan draft',
                    'url' => route('kegiatan.index', ['status' => 'draft']),
                    'icon' => 'check',
                    'color' => 'yellow'
                ],
                [
                    'title' => 'Laporan Kegiatan',
                    'description' => 'Lihat laporan kegiatan',
                    'url' => route('laporan.show', ['type' => 'kegiatan']),
                    'icon' => 'eye',
                    'color' => 'blue'
                ]
            ];
        } elseif ($user->hasRole('bendahara')) {
            $actions = [
                [
                    'title' => 'Tambah Realisasi',
                    'description' => 'Input realisasi anggaran',
                    'url' => route('realisasi.create'),
                    'icon' => 'plus',
                    'color' => 'green'
                ],
                [
                    'title' => 'Kelola Realisasi',
                    'description' => 'Lihat semua realisasi',
                    'url' => route('realisasi.index'),
                    'icon' => 'eye',
                    'color' => 'blue'
                ],
                [
                    'title' => 'Laporan Realisasi',
                    'description' => 'Lihat laporan realisasi',
                    'url' => route('laporan.show', ['type' => 'realisasi']),
                    'icon' => 'eye',
                    'color' => 'purple'
                ]
            ];
        } elseif ($user->hasRole('operator')) {
            $actions = [
                [
                    'title' => 'Tambah Kegiatan',
                    'description' => 'Buat kegiatan baru',
                    'url' => route('kegiatan.create'),
                    'icon' => 'plus',
                    'color' => 'green'
                ],
                [
                    'title' => 'Kegiatan Saya',
                    'description' => 'Lihat kegiatan yang saya buat',
                    'url' => route('kegiatan.index', ['created_by' => $user->id]),
                    'icon' => 'eye',
                    'color' => 'blue'
                ]
            ];
        } elseif ($user->hasRole('auditor')) {
            $actions = [
                [
                    'title' => 'Log Aktivitas',
                    'description' => 'Lihat log sistem',
                    'url' => route('log.index'),
                    'icon' => 'eye',
                    'color' => 'gray'
                ],
                [
                    'title' => 'Export Laporan',
                    'description' => 'Export semua laporan',
                    'url' => route('laporan.index'),
                    'icon' => 'download',
                    'color' => 'blue'
                ]
            ];
        }

        return $actions;
    }
}
