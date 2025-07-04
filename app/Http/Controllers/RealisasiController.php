<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Realisasi;
use App\Models\Kegiatan;
use App\Models\BuktiFile;
use App\Models\TahunAnggaran;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RealisasiController extends Controller
{
    // Middleware akan dihandle di routes

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahunAktif = TahunAnggaran::getTahunAktif();

        if (!$tahunAktif) {
            return redirect()->route('dashboard')
                ->with('error', 'Belum ada tahun anggaran aktif.');
        }

        $query = Realisasi::with(['kegiatan', 'pembuat', 'buktiFiles'])
            ->whereHas('kegiatan', function($q) use ($tahunAktif) {
                $q->where('tahun_id', $tahunAktif->id);
            });

        // Filter berdasarkan role
        if (auth()->user()->hasRole('bendahara')) {
            $query->where('dibuat_oleh', auth()->id());
        }

        // Apply filters
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('bulan')) {
            $query->whereRaw("CAST(strftime('%m', tanggal) AS INTEGER) = ?", [$request->bulan]);
        }

        $realisasi = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Get kegiatan yang sudah disetujui untuk filter
        $kegiatanList = Kegiatan::byTahun($tahunAktif->id)
            ->byStatus('disetujui')
            ->orderBy('nama_kegiatan')
            ->get();

        return view('realisasi.index', compact('realisasi', 'tahunAktif', 'kegiatanList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Realisasi::class);

        $tahunAktif = TahunAnggaran::getTahunAktif();

        if (!$tahunAktif) {
            return redirect()->route('realisasi.index')
                ->with('error', 'Belum ada tahun anggaran aktif.');
        }

        // Get kegiatan yang sudah disetujui
        $kegiatanList = Kegiatan::byTahun($tahunAktif->id)
            ->byStatus('disetujui')
            ->orderBy('nama_kegiatan')
            ->get();

        if ($kegiatanList->isEmpty()) {
            return redirect()->route('realisasi.index')
                ->with('error', 'Belum ada kegiatan yang disetujui untuk direalisasikan.');
        }

        $selectedKegiatan = null;
        if ($request->filled('kegiatan_id')) {
            $selectedKegiatan = $kegiatanList->find($request->kegiatan_id);
        }

        return view('realisasi.create', compact('tahunAktif', 'kegiatanList', 'selectedKegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Realisasi::class);

        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'jumlah_realisasi' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string|max:1000',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $kegiatan = Kegiatan::findOrFail($validated['kegiatan_id']);

        // Check if kegiatan is approved
        if (!$kegiatan->canBeRealized()) {
            return redirect()->back()
                ->with('error', 'Kegiatan belum disetujui atau tidak dapat direalisasikan.');
        }

        // Check if total realisasi tidak melebihi pagu
        $totalRealisasiSebelumnya = $kegiatan->getTotalRealisasi();
        $totalBaru = $totalRealisasiSebelumnya + $validated['jumlah_realisasi'];

        if ($totalBaru > $kegiatan->pagu_anggaran) {
            $sisaAnggaran = $kegiatan->pagu_anggaran - $totalRealisasiSebelumnya;
            return redirect()->back()
                ->withInput()
                ->with('error', "Jumlah realisasi melebihi sisa anggaran. Sisa anggaran: Rp " . number_format($sisaAnggaran, 0, ',', '.'));
        }

        DB::beginTransaction();
        try {
            $realisasi = Realisasi::create([
                'kegiatan_id' => $validated['kegiatan_id'],
                'jumlah_realisasi' => $validated['jumlah_realisasi'],
                'tanggal' => $validated['tanggal'],
                'deskripsi' => $validated['deskripsi'],
                'dibuat_oleh' => auth()->id(),
                'status' => 'sebagian', // Default status
            ]);

            // Handle file uploads
            if ($request->hasFile('bukti_files')) {
                foreach ($request->file('bukti_files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('bukti-realisasi', $fileName, 'public');

                    BuktiFile::create([
                        'realisasi_id' => $realisasi->id,
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => auth()->id(),
                    ]);
                }
            }

            // Update status realisasi berdasarkan persentase
            $persentaseRealisasi = $kegiatan->fresh()->getPersentaseRealisasi();
            if ($persentaseRealisasi >= 100) {
                $realisasi->update(['status' => 'selesai']);
            } elseif ($persentaseRealisasi > 0) {
                $realisasi->update(['status' => 'sebagian']);
            }

            // Log activity
            LogAktivitas::log(
                "Menambahkan realisasi untuk kegiatan: {$kegiatan->nama_kegiatan} sebesar Rp " . number_format($validated['jumlah_realisasi'], 0, ',', '.'),
                'realisasi',
                $realisasi->id,
                null,
                $realisasi->toArray()
            );

            DB::commit();

            return redirect()->route('realisasi.index')
                ->with('success', 'Realisasi berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan realisasi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Realisasi $realisasi)
    {
        $this->authorize('view', $realisasi);

        $realisasi->load(['kegiatan.tahunAnggaran', 'pembuat', 'buktiFiles.uploader']);

        return view('realisasi.show', compact('realisasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Realisasi $realisasi)
    {
        $this->authorize('update', $realisasi);

        $tahunAktif = TahunAnggaran::getTahunAktif();

        if (!$tahunAktif) {
            return redirect()->route('realisasi.index')
                ->with('error', 'Belum ada tahun anggaran aktif.');
        }

        // Get kegiatan yang sudah disetujui
        $kegiatanList = Kegiatan::byTahun($tahunAktif->id)
            ->byStatus('disetujui')
            ->orderBy('nama_kegiatan')
            ->get();

        return view('realisasi.edit', compact('realisasi', 'tahunAktif', 'kegiatanList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Realisasi $realisasi)
    {
        $this->authorize('update', $realisasi);

        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'jumlah_realisasi' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string|max:1000',
            'bukti_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $kegiatan = Kegiatan::findOrFail($validated['kegiatan_id']);

        // Check if total realisasi tidak melebihi pagu (exclude current realisasi)
        $totalRealisasiLain = $kegiatan->realisasi()
            ->where('id', '!=', $realisasi->id)
            ->sum('jumlah_realisasi');
        $totalBaru = $totalRealisasiLain + $validated['jumlah_realisasi'];

        if ($totalBaru > $kegiatan->pagu_anggaran) {
            $sisaAnggaran = $kegiatan->pagu_anggaran - $totalRealisasiLain;
            return redirect()->back()
                ->withInput()
                ->with('error', "Jumlah realisasi melebihi sisa anggaran. Sisa anggaran: Rp " . number_format($sisaAnggaran, 0, ',', '.'));
        }

        DB::beginTransaction();
        try {
            $oldData = $realisasi->toArray();

            $realisasi->update([
                'kegiatan_id' => $validated['kegiatan_id'],
                'jumlah_realisasi' => $validated['jumlah_realisasi'],
                'tanggal' => $validated['tanggal'],
                'deskripsi' => $validated['deskripsi'],
            ]);

            // Handle file uploads
            if ($request->hasFile('bukti_files')) {
                foreach ($request->file('bukti_files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('bukti-realisasi', $fileName, 'public');

                    BuktiFile::create([
                        'realisasi_id' => $realisasi->id,
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => auth()->id(),
                    ]);
                }
            }

            // Update status realisasi berdasarkan persentase
            $persentaseRealisasi = $kegiatan->fresh()->getPersentaseRealisasi();
            if ($persentaseRealisasi >= 100) {
                $realisasi->update(['status' => 'selesai']);
            } elseif ($persentaseRealisasi > 0) {
                $realisasi->update(['status' => 'sebagian']);
            } else {
                $realisasi->update(['status' => 'belum']);
            }

            // Log activity
            LogAktivitas::log(
                "Mengubah realisasi untuk kegiatan: {$kegiatan->nama_kegiatan}",
                'realisasi',
                $realisasi->id,
                $oldData,
                $realisasi->fresh()->toArray()
            );

            DB::commit();

            return redirect()->route('realisasi.index')
                ->with('success', 'Realisasi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui realisasi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Realisasi $realisasi)
    {
        $this->authorize('delete', $realisasi);

        DB::beginTransaction();
        try {
            $namaKegiatan = $realisasi->kegiatan->nama_kegiatan;
            $jumlahRealisasi = $realisasi->jumlah_realisasi;

            // Log activity before delete
            LogAktivitas::log(
                "Menghapus realisasi untuk kegiatan: {$namaKegiatan} sebesar Rp " . number_format($jumlahRealisasi, 0, ',', '.'),
                'realisasi',
                $realisasi->id,
                $realisasi->toArray(),
                null
            );

            // Delete associated files
            foreach ($realisasi->buktiFiles as $buktiFile) {
                $buktiFile->deleteFile();
                $buktiFile->delete();
            }

            $realisasi->delete();

            DB::commit();

            return redirect()->route('realisasi.index')
                ->with('success', 'Realisasi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('realisasi.index')
                ->with('error', 'Terjadi kesalahan saat menghapus realisasi.');
        }
    }

    /**
     * Delete bukti file
     */
    public function deleteBukti(BuktiFile $buktiFile)
    {
        $this->authorize('update', $buktiFile->realisasi);

        DB::beginTransaction();
        try {
            $fileName = $buktiFile->file_name;

            // Log activity
            LogAktivitas::log(
                "Menghapus bukti file: {$fileName} dari realisasi kegiatan: {$buktiFile->realisasi->kegiatan->nama_kegiatan}",
                'bukti_files',
                $buktiFile->id,
                $buktiFile->toArray(),
                null
            );

            $buktiFile->deleteFile();
            $buktiFile->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Bukti file berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus bukti file.');
        }
    }
}
