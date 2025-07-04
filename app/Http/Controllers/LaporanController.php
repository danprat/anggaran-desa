<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\TahunAnggaran;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LaporanController extends Controller
{
    use AuthorizesRequests;

    // Middleware akan dihandle di routes

    /**
     * Display laporan dashboard
     */
    public function index(Request $request)
    {
        $tahunList = TahunAnggaran::orderBy('tahun', 'desc')->get();
        $selectedTahun = $request->filled('tahun_id')
            ? TahunAnggaran::find($request->tahun_id)
            : TahunAnggaran::getTahunAktif();

        if (!$selectedTahun) {
            return view('laporan.index', [
                'tahunList' => $tahunList,
                'selectedTahun' => null,
                'stats' => null,
                'kegiatanByBidang' => collect(),
                'realisasiPerBulan' => collect(),
            ]);
        }

        // Statistik umum
        $stats = [
            'total_kegiatan' => Kegiatan::byTahun($selectedTahun->id)->count(),
            'kegiatan_disetujui' => Kegiatan::byTahun($selectedTahun->id)->byStatus('disetujui')->count(),
            'total_pagu' => Kegiatan::byTahun($selectedTahun->id)->sum('pagu_anggaran'),
            'total_realisasi' => Realisasi::whereHas('kegiatan', function($q) use ($selectedTahun) {
                $q->where('tahun_id', $selectedTahun->id);
            })->sum('jumlah_realisasi'),
        ];

        $stats['persentase_realisasi'] = $stats['total_pagu'] > 0
            ? ($stats['total_realisasi'] / $stats['total_pagu']) * 100
            : 0;

        $stats['sisa_anggaran'] = $stats['total_pagu'] - $stats['total_realisasi'];

        // Data per bidang
        $kegiatanByBidang = Kegiatan::byTahun($selectedTahun->id)
            ->select('bidang')
            ->selectRaw('COUNT(*) as jumlah_kegiatan')
            ->selectRaw('SUM(pagu_anggaran) as total_pagu')
            ->selectRaw('(SELECT SUM(jumlah_realisasi) FROM realisasi WHERE kegiatan_id IN (SELECT id FROM kegiatan WHERE bidang = kegiatan.bidang AND tahun_id = ?)) as total_realisasi', [$selectedTahun->id])
            ->groupBy('bidang')
            ->get()
            ->map(function($item) {
                $item->persentase_realisasi = $item->total_pagu > 0
                    ? ($item->total_realisasi / $item->total_pagu) * 100
                    : 0;
                return $item;
            });

        // Data realisasi per bulan (SQLite compatible)
        $realisasiPerBulan = Realisasi::whereHas('kegiatan', function($q) use ($selectedTahun) {
                $q->where('tahun_id', $selectedTahun->id);
            })
            ->selectRaw("CAST(strftime('%m', tanggal) AS INTEGER) as bulan")
            ->selectRaw('SUM(jumlah_realisasi) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!$realisasiPerBulan->has($i)) {
                $realisasiPerBulan->put($i, (object)['bulan' => $i, 'total' => 0]);
            }
        }
        $realisasiPerBulan = $realisasiPerBulan->sortKeys();

        return view('laporan.index', compact(
            'tahunList',
            'selectedTahun',
            'stats',
            'kegiatanByBidang',
            'realisasiPerBulan'
        ));
    }

    /**
     * Show detailed report
     */
    public function show(Request $request, $type)
    {
        $tahunList = TahunAnggaran::orderBy('tahun', 'desc')->get();
        $selectedTahun = $request->filled('tahun_id')
            ? TahunAnggaran::find($request->tahun_id)
            : TahunAnggaran::getTahunAktif();

        if (!$selectedTahun) {
            return redirect()->route('laporan.index')
                ->with('error', 'Pilih tahun anggaran terlebih dahulu.');
        }

        switch ($type) {
            case 'kegiatan':
                return $this->laporanKegiatan($request, $selectedTahun, $tahunList);
            case 'realisasi':
                return $this->laporanRealisasi($request, $selectedTahun, $tahunList);
            case 'keuangan':
                return $this->laporanKeuangan($request, $selectedTahun, $tahunList);
            default:
                return redirect()->route('laporan.index');
        }
    }

    private function laporanKegiatan($request, $selectedTahun, $tahunList)
    {
        $query = Kegiatan::with(['pembuatKegiatan', 'realisasi'])
            ->byTahun($selectedTahun->id);

        // Apply filters
        if ($request->filled('bidang')) {
            $query->byBidang($request->bidang);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $kegiatan = $query->orderBy('created_at', 'desc')->paginate(20);

        $bidangList = Kegiatan::byTahun($selectedTahun->id)
            ->distinct()
            ->pluck('bidang')
            ->sort();

        return view('laporan.kegiatan', compact(
            'kegiatan',
            'selectedTahun',
            'tahunList',
            'bidangList'
        ));
    }

    private function laporanRealisasi($request, $selectedTahun, $tahunList)
    {
        $query = Realisasi::with(['kegiatan', 'pembuat', 'buktiFiles'])
            ->whereHas('kegiatan', function($q) use ($selectedTahun) {
                $q->where('tahun_id', $selectedTahun->id);
            });

        // Apply filters
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('bulan')) {
            $query->whereRaw("CAST(strftime('%m', tanggal) AS INTEGER) = ?", [$request->bulan]);
        }

        $realisasi = $query->orderBy('tanggal', 'desc')->paginate(20);

        $kegiatanList = Kegiatan::byTahun($selectedTahun->id)
            ->byStatus('disetujui')
            ->orderBy('nama_kegiatan')
            ->get();

        return view('laporan.realisasi', compact(
            'realisasi',
            'selectedTahun',
            'tahunList',
            'kegiatanList'
        ));
    }

    private function laporanKeuangan($request, $selectedTahun, $tahunList)
    {
        // Summary per bidang
        $summaryBidang = Kegiatan::byTahun($selectedTahun->id)
            ->select('bidang')
            ->selectRaw('COUNT(*) as jumlah_kegiatan')
            ->selectRaw('SUM(pagu_anggaran) as total_pagu')
            ->selectRaw('(SELECT SUM(jumlah_realisasi) FROM realisasi WHERE kegiatan_id IN (SELECT id FROM kegiatan WHERE bidang = kegiatan.bidang AND tahun_id = ?)) as total_realisasi', [$selectedTahun->id])
            ->groupBy('bidang')
            ->get()
            ->map(function($item) {
                $item->sisa_anggaran = $item->total_pagu - $item->total_realisasi;
                $item->persentase_realisasi = $item->total_pagu > 0
                    ? ($item->total_realisasi / $item->total_pagu) * 100
                    : 0;
                return $item;
            });

        // Summary per sumber dana
        $summarySumberDana = Kegiatan::byTahun($selectedTahun->id)
            ->select('sumber_dana')
            ->selectRaw('COUNT(*) as jumlah_kegiatan')
            ->selectRaw('SUM(pagu_anggaran) as total_pagu')
            ->selectRaw('(SELECT SUM(jumlah_realisasi) FROM realisasi WHERE kegiatan_id IN (SELECT id FROM kegiatan WHERE sumber_dana = kegiatan.sumber_dana AND tahun_id = ?)) as total_realisasi', [$selectedTahun->id])
            ->groupBy('sumber_dana')
            ->get()
            ->map(function($item) {
                $item->sisa_anggaran = $item->total_pagu - $item->total_realisasi;
                $item->persentase_realisasi = $item->total_pagu > 0
                    ? ($item->total_realisasi / $item->total_pagu) * 100
                    : 0;
                return $item;
            });

        return view('laporan.keuangan', compact(
            'summaryBidang',
            'summarySumberDana',
            'selectedTahun',
            'tahunList'
        ));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request, $type)
    {
        $this->authorize('export', 'laporan');

        $tahun = TahunAnggaran::findOrFail($request->tahun_id);

        switch ($type) {
            case 'kegiatan':
                return $this->exportKegiatanPdf($request, $tahun);
            case 'realisasi':
                return $this->exportRealisasiPdf($request, $tahun);
            case 'keuangan':
                return $this->exportKeuanganPdf($request, $tahun);
            default:
                return redirect()->back()->with('error', 'Tipe laporan tidak valid.');
        }
    }

    /**
     * Export laporan ke Excel
     */
    public function exportExcel(Request $request, $type)
    {
        $this->authorize('export', 'laporan');

        $tahun = TahunAnggaran::findOrFail($request->tahun_id);

        switch ($type) {
            case 'kegiatan':
                return $this->exportKegiatanExcel($request, $tahun);
            case 'realisasi':
                return $this->exportRealisasiExcel($request, $tahun);
            case 'keuangan':
                return $this->exportKeuanganExcel($request, $tahun);
            default:
                return redirect()->back()->with('error', 'Tipe laporan tidak valid.');
        }
    }

    private function exportKegiatanPdf($request, $tahun)
    {
        $kegiatan = Kegiatan::with(['pembuatKegiatan', 'realisasi'])
            ->byTahun($tahun->id)
            ->orderBy('bidang')
            ->orderBy('nama_kegiatan')
            ->get();

        $data = [
            'kegiatan' => $kegiatan,
            'tahun' => $tahun,
            'tanggal_cetak' => now(),
            'total_pagu' => $kegiatan->sum('pagu_anggaran'),
            'total_realisasi' => $kegiatan->sum(function($k) { return $k->getTotalRealisasi(); }),
        ];

        $pdf = Pdf::loadView('laporan.pdf.kegiatan', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("Laporan_Kegiatan_{$tahun->tahun}.pdf");
    }

    private function exportRealisasiPdf($request, $tahun)
    {
        $realisasi = Realisasi::with(['kegiatan', 'pembuat'])
            ->whereHas('kegiatan', function($q) use ($tahun) {
                $q->where('tahun_id', $tahun->id);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $data = [
            'realisasi' => $realisasi,
            'tahun' => $tahun,
            'tanggal_cetak' => now(),
            'total_realisasi' => $realisasi->sum('jumlah_realisasi'),
        ];

        $pdf = Pdf::loadView('laporan.pdf.realisasi', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("Laporan_Realisasi_{$tahun->tahun}.pdf");
    }

    private function exportKeuanganPdf($request, $tahun)
    {
        $summaryBidang = Kegiatan::byTahun($tahun->id)
            ->select('bidang')
            ->selectRaw('COUNT(*) as jumlah_kegiatan')
            ->selectRaw('SUM(pagu_anggaran) as total_pagu')
            ->selectRaw('(SELECT SUM(jumlah_realisasi) FROM realisasi WHERE kegiatan_id IN (SELECT id FROM kegiatan WHERE bidang = kegiatan.bidang AND tahun_id = ?)) as total_realisasi', [$tahun->id])
            ->groupBy('bidang')
            ->get();

        $data = [
            'summaryBidang' => $summaryBidang,
            'tahun' => $tahun,
            'tanggal_cetak' => now(),
            'total_pagu' => $summaryBidang->sum('total_pagu'),
            'total_realisasi' => $summaryBidang->sum('total_realisasi'),
        ];

        $pdf = Pdf::loadView('laporan.pdf.keuangan', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Laporan_Keuangan_{$tahun->tahun}.pdf");
    }

    private function exportKegiatanExcel($request, $tahun)
    {
        return Excel::download(new \App\Exports\KegiatanExport($tahun), "Laporan_Kegiatan_{$tahun->tahun}.xlsx");
    }

    private function exportRealisasiExcel($request, $tahun)
    {
        return Excel::download(new \App\Exports\RealisasiExport($tahun), "Laporan_Realisasi_{$tahun->tahun}.xlsx");
    }

    private function exportKeuanganExcel($request, $tahun)
    {
        return Excel::download(new \App\Exports\KeuanganExport($tahun), "Laporan_Keuangan_{$tahun->tahun}.xlsx");
    }
}
