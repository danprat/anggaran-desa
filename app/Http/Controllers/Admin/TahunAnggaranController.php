<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAnggaran;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TahunAnggaranController extends Controller
{
    use AuthorizesRequests;

    // Middleware akan dihandle di routes

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAnggaran = TahunAnggaran::withCount('kegiatan')
            ->orderBy('tahun', 'desc')
            ->get();

        return view('admin.tahun-anggaran.index', compact('tahunAnggaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tahun-anggaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2050|unique:tahun_anggaran,tahun',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();
        try {
            // If setting as aktif, deactivate others
            if ($validated['status'] === 'aktif') {
                TahunAnggaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
            }

            $tahunAnggaran = TahunAnggaran::create($validated);

            // Log activity
            LogAktivitas::log(
                "Menambahkan tahun anggaran: {$tahunAnggaran->tahun} dengan status {$tahunAnggaran->status}",
                'tahun_anggaran',
                $tahunAnggaran->id,
                null,
                $tahunAnggaran->toArray()
            );

            DB::commit();

            return redirect()->route('admin.tahun-anggaran.index')
                ->with('success', 'Tahun anggaran berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan tahun anggaran.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAnggaran $tahunAnggaran)
    {
        $tahunAnggaran->load(['kegiatan.realisasi']);

        $stats = [
            'total_kegiatan' => $tahunAnggaran->kegiatan->count(),
            'kegiatan_disetujui' => $tahunAnggaran->kegiatan->where('status', 'disetujui')->count(),
            'total_pagu' => $tahunAnggaran->kegiatan->sum('pagu_anggaran'),
            'total_realisasi' => $tahunAnggaran->kegiatan->sum(function($k) { return $k->getTotalRealisasi(); }),
        ];

        $stats['persentase_realisasi'] = $stats['total_pagu'] > 0
            ? ($stats['total_realisasi'] / $stats['total_pagu']) * 100
            : 0;

        return view('admin.tahun-anggaran.show', compact('tahunAnggaran', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAnggaran $tahunAnggaran)
    {
        return view('admin.tahun-anggaran.edit', compact('tahunAnggaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAnggaran $tahunAnggaran)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2050|unique:tahun_anggaran,tahun,' . $tahunAnggaran->id,
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();
        try {
            $oldData = $tahunAnggaran->toArray();

            // If setting as aktif, deactivate others
            if ($validated['status'] === 'aktif' && $tahunAnggaran->status !== 'aktif') {
                TahunAnggaran::where('status', 'aktif')->update(['status' => 'nonaktif']);
            }

            $tahunAnggaran->update($validated);

            // Log activity
            LogAktivitas::log(
                "Mengubah tahun anggaran: {$tahunAnggaran->tahun}",
                'tahun_anggaran',
                $tahunAnggaran->id,
                $oldData,
                $tahunAnggaran->fresh()->toArray()
            );

            DB::commit();

            return redirect()->route('admin.tahun-anggaran.index')
                ->with('success', 'Tahun anggaran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui tahun anggaran.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAnggaran $tahunAnggaran)
    {
        // Check if has kegiatan
        if ($tahunAnggaran->kegiatan()->count() > 0) {
            return redirect()->route('admin.tahun-anggaran.index')
                ->with('error', 'Tahun anggaran tidak dapat dihapus karena masih memiliki kegiatan.');
        }

        DB::beginTransaction();
        try {
            // Log activity before delete
            LogAktivitas::log(
                "Menghapus tahun anggaran: {$tahunAnggaran->tahun}",
                'tahun_anggaran',
                $tahunAnggaran->id,
                $tahunAnggaran->toArray(),
                null
            );

            $tahunAnggaran->delete();

            DB::commit();

            return redirect()->route('admin.tahun-anggaran.index')
                ->with('success', 'Tahun anggaran berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.tahun-anggaran.index')
                ->with('error', 'Terjadi kesalahan saat menghapus tahun anggaran.');
        }
    }

    /**
     * Set tahun anggaran as aktif
     */
    public function setAktif(TahunAnggaran $tahunAnggaran)
    {
        DB::beginTransaction();
        try {
            // Deactivate all others
            TahunAnggaran::where('status', 'aktif')->update(['status' => 'nonaktif']);

            // Activate this one
            $tahunAnggaran->update(['status' => 'aktif']);

            // Log activity
            LogAktivitas::log(
                "Mengaktifkan tahun anggaran: {$tahunAnggaran->tahun}",
                'tahun_anggaran',
                $tahunAnggaran->id,
                ['status' => 'nonaktif'],
                ['status' => 'aktif']
            );

            DB::commit();

            return redirect()->route('admin.tahun-anggaran.index')
                ->with('success', "Tahun anggaran {$tahunAnggaran->tahun} berhasil diaktifkan.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.tahun-anggaran.index')
                ->with('error', 'Terjadi kesalahan saat mengaktifkan tahun anggaran.');
        }
    }
}
