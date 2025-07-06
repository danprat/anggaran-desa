<?php

namespace App\Http\Controllers;

use App\Models\DesaProfile;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\TahunAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Display the landing page
     */
    public function index(Request $request)
    {
        $desaProfile = DesaProfile::getActive();
        $tahunAktif = TahunAnggaran::getTahunAktif();

        // Get available years for filter
        $availableYears = TahunAnggaran::orderBy('tahun', 'desc')->get();

        // Get selected year from request or use active year
        $requestedYear = $request->get('tahun');
        if ($requestedYear) {
            // Cari berdasarkan tahun asli (2024, 2025, dst)
            $selectedTahun = TahunAnggaran::where('tahun', $requestedYear)->first();
        } else {
            $selectedTahun = $tahunAktif;
        }

        // Get basic statistics
        $stats = [];
        $realisasiStats = [];

        if ($selectedTahun) {
            $stats = $this->getBasicStats($selectedTahun);
            $realisasiStats = $this->getRealisasiStats($selectedTahun);
        }

        return view('landing', compact(
            'desaProfile',
            'tahunAktif',
            'selectedTahun',
            'availableYears',
            'stats',
            'realisasiStats'
        ));
    }

    /**
     * Get basic statistics for a given year
     */
    private function getBasicStats($tahun)
    {
        return [
            'total_kegiatan' => Kegiatan::where('tahun_id', $tahun->id)->count(),
            'kegiatan_approved' => Kegiatan::where('tahun_id', $tahun->id)
                ->where('status', 'disetujui')->count(),
            'total_pagu' => Kegiatan::where('tahun_id', $tahun->id)
                ->where('status', 'disetujui')
                ->sum('pagu_anggaran'),
        ];
    }

    /**
     * Get realisasi statistics for public transparency
     */
    private function getRealisasiStats($tahun)
    {
        // Total anggaran yang disetujui
        $totalAnggaran = Kegiatan::where('tahun_id', $tahun->id)
            ->where('status', 'disetujui')
            ->sum('pagu_anggaran');

        // Total realisasi
        $totalRealisasi = Realisasi::whereHas('kegiatan', function($q) use ($tahun) {
                $q->where('tahun_id', $tahun->id)
                  ->where('status', 'disetujui');
            })
            ->sum('jumlah_realisasi');

        // Persentase realisasi
        $persentaseRealisasi = $totalAnggaran > 0 ? ($totalRealisasi / $totalAnggaran) * 100 : 0;

        // Statistik per bidang
        $statsBidang = $this->getStatsBidang($tahun);

        // Top 5 kegiatan dengan realisasi tertinggi
        $topKegiatan = $this->getTopKegiatan($tahun);

        // Data untuk charts
        $chartData = $this->getChartData($tahun);

        return [
            'total_anggaran' => $totalAnggaran,
            'total_realisasi' => $totalRealisasi,
            'sisa_anggaran' => $totalAnggaran - $totalRealisasi,
            'persentase_realisasi' => round($persentaseRealisasi, 2),
            'stats_bidang' => $statsBidang,
            'top_kegiatan' => $topKegiatan,
            'chart_data' => $chartData,
        ];
    }

    /**
     * Get statistics per bidang/kategori
     */
    private function getStatsBidang($tahun)
    {
        return DB::table('kegiatan')
            ->select(
                'bidang',
                DB::raw('SUM(pagu_anggaran) as total_anggaran'),
                DB::raw('COALESCE(SUM(realisasi_total.total_realisasi), 0) as total_realisasi'),
                DB::raw('COUNT(*) as jumlah_kegiatan')
            )
            ->leftJoin(
                DB::raw('(SELECT kegiatan_id, SUM(jumlah_realisasi) as total_realisasi FROM realisasi GROUP BY kegiatan_id) as realisasi_total'),
                'kegiatan.id', '=', 'realisasi_total.kegiatan_id'
            )
            ->where('tahun_id', $tahun->id)
            ->where('status', 'disetujui')
            ->groupBy('bidang')
            ->orderBy('total_anggaran', 'desc')
            ->get()
            ->map(function($item) {
                $item->persentase_realisasi = $item->total_anggaran > 0
                    ? round(($item->total_realisasi / $item->total_anggaran) * 100, 2)
                    : 0;
                return $item;
            });
    }

    /**
     * Get top 5 kegiatan with highest realisasi
     */
    private function getTopKegiatan($tahun)
    {
        return Kegiatan::select('kegiatan.*')
            ->selectRaw('COALESCE(SUM(realisasi.jumlah_realisasi), 0) as total_realisasi')
            ->leftJoin('realisasi', 'kegiatan.id', '=', 'realisasi.kegiatan_id')
            ->where('kegiatan.tahun_id', $tahun->id)
            ->where('kegiatan.status', 'disetujui')
            ->groupBy('kegiatan.id')
            ->orderBy('total_realisasi', 'desc')
            ->limit(5)
            ->get()
            ->map(function($kegiatan) {
                $kegiatan->persentase_realisasi = $kegiatan->pagu_anggaran > 0
                    ? round(($kegiatan->total_realisasi / $kegiatan->pagu_anggaran) * 100, 2)
                    : 0;
                return $kegiatan;
            });
    }

    /**
     * Get chart data for visualizations
     */
    private function getChartData($tahun)
    {
        // Data untuk pie chart bidang
        $bidangData = $this->getStatsBidang($tahun)->map(function($item) {
            return [
                'bidang' => $item->bidang,
                'total_anggaran' => (float) $item->total_anggaran,
                'total_realisasi' => (float) $item->total_realisasi,
                'jumlah_kegiatan' => $item->jumlah_kegiatan,
                'persentase_realisasi' => $item->persentase_realisasi,
            ];
        })->values()->toArray();

        // Data untuk progress chart realisasi bulanan
        $monthlyData = $this->getMonthlyRealisasi($tahun);

        // Data untuk status kegiatan
        $statusData = $this->getStatusData($tahun);

        return [
            'bidang' => $bidangData,
            'monthly' => $monthlyData,
            'status' => $statusData,
        ];
    }

    /**
     * Get monthly realisasi data
     */
    private function getMonthlyRealisasi($tahun)
    {
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $totalRealisasi = Realisasi::whereHas('kegiatan', function($q) use ($tahun) {
                    $q->where('tahun_id', $tahun->id)
                      ->where('status', 'disetujui');
                })
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $tahun->tahun)
                ->sum('jumlah_realisasi');

            $monthlyData[] = [
                'month' => $month,
                'month_name' => date('M', mktime(0, 0, 0, $month, 1)),
                'total' => (float) $totalRealisasi,
            ];
        }

        return $monthlyData;
    }

    /**
     * Get status distribution data
     */
    private function getStatusData($tahun)
    {
        return [
            'draft' => Kegiatan::where('tahun_id', $tahun->id)->where('status', 'draft')->count(),
            'verifikasi' => Kegiatan::where('tahun_id', $tahun->id)->where('status', 'verifikasi')->count(),
            'disetujui' => Kegiatan::where('tahun_id', $tahun->id)->where('status', 'disetujui')->count(),
            'ditolak' => Kegiatan::where('tahun_id', $tahun->id)->where('status', 'ditolak')->count(),
        ];
    }
}
