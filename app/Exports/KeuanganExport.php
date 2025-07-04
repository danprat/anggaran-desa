<?php

namespace App\Exports;

use App\Models\Kegiatan;
use App\Models\TahunAnggaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $tahun;

    public function __construct(TahunAnggaran $tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Kegiatan::byTahun($this->tahun->id)
            ->select('bidang')
            ->selectRaw('COUNT(*) as jumlah_kegiatan')
            ->selectRaw('SUM(pagu_anggaran) as total_pagu')
            ->selectRaw('(SELECT SUM(jumlah_realisasi) FROM realisasi WHERE kegiatan_id IN (SELECT id FROM kegiatan WHERE bidang = kegiatan.bidang AND tahun_id = ?)) as total_realisasi', [$this->tahun->id])
            ->groupBy('bidang')
            ->get()
            ->map(function($item) {
                $item->sisa_anggaran = $item->total_pagu - $item->total_realisasi;
                $item->persentase_realisasi = $item->total_pagu > 0 
                    ? ($item->total_realisasi / $item->total_pagu) * 100 
                    : 0;
                return $item;
            });
    }

    public function headings(): array
    {
        return [
            'No',
            'Bidang',
            'Jumlah Kegiatan',
            'Total Pagu Anggaran',
            'Total Realisasi',
            'Sisa Anggaran',
            'Persentase Realisasi (%)',
        ];
    }

    public function map($summary): array
    {
        static $no = 1;

        return [
            $no++,
            $summary->bidang,
            $summary->jumlah_kegiatan,
            $summary->total_pagu,
            $summary->total_realisasi,
            $summary->sisa_anggaran,
            round($summary->persentase_realisasi, 2),
        ];
    }

    public function title(): string
    {
        return "Laporan Keuangan {$this->tahun->tahun}";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
