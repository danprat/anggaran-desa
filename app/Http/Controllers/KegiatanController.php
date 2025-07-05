<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\TahunAnggaran;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KegiatanController extends Controller
{
    use AuthorizesRequests;

    // Middleware akan dihandle di routes

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahunAktif = TahunAnggaran::getTahunAktif();

        if (!$tahunAktif) {
            return redirect()->route('dashboard')
                ->with('error', 'Belum ada tahun anggaran aktif. Silakan hubungi administrator.');
        }

        $query = Kegiatan::with(['pembuatKegiatan', 'tahunAnggaran'])
            ->byTahun($tahunAktif->id);

        // Filter berdasarkan role
        if (auth()->user()->hasRole('operator')) {
            $query->where('dibuat_oleh', auth()->id());
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('bidang')) {
            $query->byBidang($request->bidang);
        }

        if ($request->filled('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
        }

        $kegiatan = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get unique bidang for filter
        $bidangList = Kegiatan::byTahun($tahunAktif->id)
            ->distinct()
            ->pluck('bidang')
            ->sort();

        return view('kegiatan.index', compact('kegiatan', 'tahunAktif', 'bidangList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Kegiatan::class);

        $tahunAktif = TahunAnggaran::getTahunAktif();

        if (!$tahunAktif) {
            return redirect()->route('kegiatan.index')
                ->with('error', 'Belum ada tahun anggaran aktif.');
        }

        $bidangOptions = [
            'Pembangunan',
            'Pemberdayaan Masyarakat',
            'Pelayanan Umum',
            'Kesehatan',
            'Pendidikan',
            'Infrastruktur',
            'Ekonomi',
            'Sosial Budaya'
        ];

        $sumberDanaOptions = [
            'DD (Dana Desa)',
            'ADD (Alokasi Dana Desa)',
            'PADes (Pendapatan Asli Desa)',
            'Bantuan Provinsi',
            'Bantuan Kabupaten',
            'Swadaya Masyarakat'
        ];

        return view('kegiatan.create', compact('tahunAktif', 'bidangOptions', 'sumberDanaOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Kegiatan::class);

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'bidang' => 'required|string|max:100',
            'pagu_anggaran' => 'required|numeric|min:0',
            'sumber_dana' => 'required|string|max:100',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $tahunAktif = TahunAnggaran::getTahunAktif();

        if (!$tahunAktif) {
            return redirect()->back()
                ->with('error', 'Belum ada tahun anggaran aktif.');
        }

        // Check for duplicate nama_kegiatan in same year
        $exists = Kegiatan::byTahun($tahunAktif->id)
            ->where('nama_kegiatan', $validated['nama_kegiatan'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama kegiatan sudah ada untuk tahun anggaran ini.');
        }

        DB::beginTransaction();
        try {
            $kegiatan = Kegiatan::create([
                'tahun_id' => $tahunAktif->id,
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'bidang' => $validated['bidang'],
                'pagu_anggaran' => $validated['pagu_anggaran'],
                'sumber_dana' => $validated['sumber_dana'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'keterangan' => $validated['keterangan'],
                'dibuat_oleh' => auth()->id(),
                'status' => 'draft',
            ]);

            // Log activity
            LogAktivitas::log(
                "Menambahkan kegiatan baru: {$kegiatan->nama_kegiatan}",
                'kegiatan',
                $kegiatan->id,
                null,
                $kegiatan->toArray()
            );

            DB::commit();

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan kegiatan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan)
    {
        $this->authorize('view', $kegiatan);

        $kegiatan->load(['pembuatKegiatan', 'tahunAnggaran', 'realisasi.pembuat', 'realisasi.buktiFiles']);

        $totalRealisasi = $kegiatan->getTotalRealisasi();
        $persentaseRealisasi = $kegiatan->getPersentaseRealisasi();
        $sisaAnggaran = $kegiatan->getSisaAnggaran();

        return view('kegiatan.show', compact('kegiatan', 'totalRealisasi', 'persentaseRealisasi', 'sisaAnggaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        $this->authorize('update', $kegiatan);

        if (!$kegiatan->canBeEdited()) {
            return redirect()->route('kegiatan.index')
                ->with('error', 'Kegiatan tidak dapat diedit karena sudah disetujui atau ditolak.');
        }

        $bidangOptions = [
            'Pembangunan',
            'Pemberdayaan Masyarakat',
            'Pelayanan Umum',
            'Kesehatan',
            'Pendidikan',
            'Infrastruktur',
            'Ekonomi',
            'Sosial Budaya'
        ];

        $sumberDanaOptions = [
            'DD (Dana Desa)',
            'ADD (Alokasi Dana Desa)',
            'PADes (Pendapatan Asli Desa)',
            'Bantuan Provinsi',
            'Bantuan Kabupaten',
            'Swadaya Masyarakat'
        ];

        return view('kegiatan.edit', compact('kegiatan', 'bidangOptions', 'sumberDanaOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $this->authorize('update', $kegiatan);

        if (!$kegiatan->canBeEdited()) {
            return redirect()->route('kegiatan.index')
                ->with('error', 'Kegiatan tidak dapat diedit karena sudah disetujui atau ditolak.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'bidang' => 'required|string|max:100',
            'pagu_anggaran' => 'required|numeric|min:0',
            'sumber_dana' => 'required|string|max:100',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Check for duplicate nama_kegiatan in same year (exclude current)
        $exists = Kegiatan::byTahun($kegiatan->tahun_id)
            ->where('nama_kegiatan', $validated['nama_kegiatan'])
            ->where('id', '!=', $kegiatan->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama kegiatan sudah ada untuk tahun anggaran ini.');
        }

        DB::beginTransaction();
        try {
            $oldData = $kegiatan->toArray();

            $kegiatan->update($validated);

            // Log activity
            LogAktivitas::log(
                "Mengubah kegiatan: {$kegiatan->nama_kegiatan}",
                'kegiatan',
                $kegiatan->id,
                $oldData,
                $kegiatan->fresh()->toArray()
            );

            DB::commit();

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui kegiatan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $this->authorize('delete', $kegiatan);

        if (!$kegiatan->canBeEdited()) {
            return redirect()->route('kegiatan.index')
                ->with('error', 'Kegiatan tidak dapat dihapus karena sudah disetujui atau ditolak.');
        }

        DB::beginTransaction();
        try {
            $namaKegiatan = $kegiatan->nama_kegiatan;

            // Log activity before delete
            LogAktivitas::log(
                "Menghapus kegiatan: {$namaKegiatan}",
                'kegiatan',
                $kegiatan->id,
                $kegiatan->toArray(),
                null
            );

            $kegiatan->delete();

            DB::commit();

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('kegiatan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus kegiatan.');
        }
    }

    /**
     * Verify kegiatan (Sekretaris)
     */
    public function verify(Kegiatan $kegiatan)
    {
        $this->authorize('verify', $kegiatan);

        if ($kegiatan->status !== 'draft') {
            return redirect()->route('kegiatan.index')
                ->with('error', 'Kegiatan tidak dapat diverifikasi.');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $kegiatan->status;
            $kegiatan->update(['status' => 'verifikasi']);

            // Log activity
            LogAktivitas::log(
                "Memverifikasi kegiatan: {$kegiatan->nama_kegiatan}",
                'kegiatan',
                $kegiatan->id,
                ['status' => $oldStatus],
                ['status' => 'verifikasi']
            );

            DB::commit();

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil diverifikasi.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('kegiatan.index')
                ->with('error', 'Terjadi kesalahan saat memverifikasi kegiatan.');
        }
    }

    /**
     * Approve kegiatan (Kepala Desa)
     */
    public function approve(Kegiatan $kegiatan)
    {
        $this->authorize('approve', $kegiatan);

        if ($kegiatan->status !== 'verifikasi') {
            return redirect()->route('kegiatan.index')
                ->with('error', 'Kegiatan harus diverifikasi terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $kegiatan->status;
            $kegiatan->update(['status' => 'disetujui']);

            // Log activity
            LogAktivitas::log(
                "Menyetujui kegiatan: {$kegiatan->nama_kegiatan}",
                'kegiatan',
                $kegiatan->id,
                ['status' => $oldStatus],
                ['status' => 'disetujui']
            );

            DB::commit();

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('kegiatan.index')
                ->with('error', 'Terjadi kesalahan saat menyetujui kegiatan.');
        }
    }

    /**
     * Reject kegiatan (Kepala Desa)
     */
    public function reject(Request $request, Kegiatan $kegiatan)
    {
        $this->authorize('approve', $kegiatan);

        if ($kegiatan->status !== 'verifikasi') {
            return redirect()->route('kegiatan.index')
                ->with('error', 'Kegiatan harus diverifikasi terlebih dahulu.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $kegiatan->status;
            $kegiatan->update([
                'status' => 'ditolak',
                'keterangan' => $kegiatan->keterangan . "\n\nDitolak: " . $request->alasan_penolakan
            ]);

            // Log activity
            LogAktivitas::log(
                "Menolak kegiatan: {$kegiatan->nama_kegiatan}. Alasan: {$request->alasan_penolakan}",
                'kegiatan',
                $kegiatan->id,
                ['status' => $oldStatus],
                ['status' => 'ditolak']
            );

            DB::commit();

            return redirect()->route('kegiatan.index')
                ->with('success', 'Kegiatan berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('kegiatan.index')
                ->with('error', 'Terjadi kesalahan saat menolak kegiatan.');
        }
    }

    /**
     * Export kegiatan detail to PDF
     */
    public function exportPdf(Kegiatan $kegiatan)
    {
        $this->authorize('view', $kegiatan);

        $data = [
            'kegiatan' => $kegiatan->load(['realisasi.buktiFiles', 'tahunAnggaran', 'pembuatKegiatan']),
            'totalRealisasi' => $kegiatan->getTotalRealisasi(),
            'persentaseRealisasi' => $kegiatan->getPersentaseRealisasi(),
            'sisaAnggaran' => $kegiatan->getSisaAnggaran(),
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('kegiatan.pdf.detail', $data);

        $filename = 'Kegiatan_' . Str::slug($kegiatan->nama_kegiatan) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print kegiatan detail
     */
    public function print(Kegiatan $kegiatan)
    {
        $this->authorize('view', $kegiatan);

        $data = [
            'kegiatan' => $kegiatan->load(['realisasi.buktiFiles', 'tahunAnggaran', 'pembuatKegiatan']),
            'totalRealisasi' => $kegiatan->getTotalRealisasi(),
            'persentaseRealisasi' => $kegiatan->getPersentaseRealisasi(),
            'sisaAnggaran' => $kegiatan->getSisaAnggaran(),
        ];

        return view('kegiatan.print.detail', $data);
    }
}
