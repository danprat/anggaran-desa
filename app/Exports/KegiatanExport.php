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

class KegiatanExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $tahun;

    public function __construct(TahunAnggaran $tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Kegiatan::with(['pembuatKegiatan', 'realisasi'])
            ->byTahun($this->tahun->id)
            ->orderBy('bidang')
            ->orderBy('nama_kegiatan')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kegiatan',
            'Bidang',
            'Pagu Anggaran',
            'Sumber Dana',
            'Waktu Mulai',
            'Waktu Selesai',
            'Status',
            'Total Realisasi',
            'Sisa Anggaran',
            'Persentase (%)',
            'Dibuat Oleh',
            'Tanggal Dibuat',
        ];
    }

    public function map($kegiatan): array
    {
        static $no = 1;
        
        $totalRealisasi = $kegiatan->getTotalRealisasi();
        $sisaAnggaran = $kegiatan->getSisaAnggaran();
        $persentase = $kegiatan->getPersentaseRealisasi();

        return [
            $no++,
            $kegiatan->nama_kegiatan,
            $kegiatan->bidang,
            $kegiatan->pagu_anggaran,
            $kegiatan->sumber_dana,
            $kegiatan->waktu_mulai->format('d/m/Y'),
            $kegiatan->waktu_selesai->format('d/m/Y'),
            ucfirst($kegiatan->status),
            $totalRealisasi,
            $sisaAnggaran,
            round($persentase, 2),
            $kegiatan->pembuatKegiatan->name,
            $kegiatan->created_at->format('d/m/Y H:i'),
        ];
    }

    public function title(): string
    {
        return "Laporan Kegiatan {$this->tahun->tahun}";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
