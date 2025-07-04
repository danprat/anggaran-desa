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

        return view('dashboard', compact(
            'tahunAktif',
            'stats',
            'chartData',
            'kegiatanTerbaru',
            'realisasiTerbaru'
        ));
    }
}
