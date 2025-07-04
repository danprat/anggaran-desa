<?php

namespace App\Exports;

use App\Models\Realisasi;
use App\Models\TahunAnggaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RealisasiExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $tahun;

    public function __construct(TahunAnggaran $tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Realisasi::with(['kegiatan', 'pembuat', 'buktiFiles'])
            ->whereHas('kegiatan', function($q) {
                $q->where('tahun_id', $this->tahun->id);
            })
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kegiatan',
            'Bidang',
            'Tanggal Realisasi',
            'Jumlah Realisasi',
            'Status',
            'Deskripsi',
            'Jumlah Bukti',
            'Dibuat Oleh',
            'Tanggal Input',
        ];
    }

    public function map($realisasi): array
    {
        static $no = 1;

        return [
            $no++,
            $realisasi->kegiatan->nama_kegiatan,
            $realisasi->kegiatan->bidang,
            $realisasi->tanggal->format('d/m/Y'),
            $realisasi->jumlah_realisasi,
            ucfirst($realisasi->status),
            $realisasi->deskripsi ?? '-',
            $realisasi->buktiFiles->count(),
            $realisasi->pembuat->name,
            $realisasi->created_at->format('d/m/Y H:i'),
        ];
    }

    public function title(): string
    {
        return "Laporan Realisasi {$this->tahun->tahun}";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
