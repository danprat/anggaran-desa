<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DesaProfileController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        $profiles = DesaProfile::orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.desa-profile.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('manage-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.desa-profile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('manage-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'alamat_lengkap' => 'required|string',
            'kepala_desa' => 'required|string|max:255',
            'nip_kepala_desa' => 'nullable|string|max:30',
            'periode_jabatan_mulai' => 'nullable|date',
            'periode_jabatan_selesai' => 'nullable|date|after_or_equal:periode_jabatan_mulai',
            'logo_desa' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_kabupaten' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_provinsi' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah_singkat' => 'nullable|string',
            'luas_wilayah' => 'nullable|numeric|min:0',
            'jumlah_penduduk' => 'nullable|integer|min:0',
            'jumlah_kk' => 'nullable|integer|min:0',
            'batas_utara' => 'nullable|string|max:255',
            'batas_selatan' => 'nullable|string|max:255',
            'batas_timur' => 'nullable|string|max:255',
            'batas_barat' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Handle logo uploads
            $logoFields = ['logo_desa', 'logo_kabupaten', 'logo_provinsi'];
            foreach ($logoFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('logos', $filename, 'public');
                    $validated[$field] = $path;
                }
            }

            // If this profile is set as active, deactivate others
            if ($validated['is_active'] ?? false) {
                DesaProfile::where('is_active', true)->update(['is_active' => false]);
            }

            $profile = DesaProfile::create($validated);

            // Log activity
            LogAktivitas::log(
                "Membuat profil desa: {$profile->nama_desa}",
                'desa_profile',
                $profile->id,
                null,
                $profile->toArray()
            );

            DB::commit();

            return redirect()->route('admin.desa-profile.index')
                ->with('success', 'Profil desa berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan profil desa.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DesaProfile $desaProfile)
    {
        if (!auth()->user()->can('view-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.desa-profile.show', compact('desaProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DesaProfile $desaProfile)
    {
        if (!auth()->user()->can('manage-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.desa-profile.edit', compact('desaProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DesaProfile $desaProfile)
    {
        if (!auth()->user()->can('manage-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'alamat_lengkap' => 'required|string',
            'kepala_desa' => 'required|string|max:255',
            'nip_kepala_desa' => 'nullable|string|max:30',
            'periode_jabatan_mulai' => 'nullable|date',
            'periode_jabatan_selesai' => 'nullable|date|after_or_equal:periode_jabatan_mulai',
            'logo_desa' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_kabupaten' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'logo_provinsi' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah_singkat' => 'nullable|string',
            'luas_wilayah' => 'nullable|numeric|min:0',
            'jumlah_penduduk' => 'nullable|integer|min:0',
            'jumlah_kk' => 'nullable|integer|min:0',
            'batas_utara' => 'nullable|string|max:255',
            'batas_selatan' => 'nullable|string|max:255',
            'batas_timur' => 'nullable|string|max:255',
            'batas_barat' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $oldData = $desaProfile->toArray();

            // Handle logo uploads
            $logoFields = ['logo_desa', 'logo_kabupaten', 'logo_provinsi'];
            foreach ($logoFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old logo
                    if ($desaProfile->$field && Storage::disk('public')->exists($desaProfile->$field)) {
                        Storage::disk('public')->delete($desaProfile->$field);
                    }

                    // Upload new logo
                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('logos', $filename, 'public');
                    $validated[$field] = $path;
                }
            }

            // If this profile is set as active, deactivate others
            if ($validated['is_active'] ?? false) {
                DesaProfile::where('id', '!=', $desaProfile->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $desaProfile->update($validated);

            // Log activity
            LogAktivitas::log(
                "Mengupdate profil desa: {$desaProfile->nama_desa}",
                'desa_profile',
                $desaProfile->id,
                $oldData,
                $desaProfile->fresh()->toArray()
            );

            DB::commit();

            return redirect()->route('admin.desa-profile.index')
                ->with('success', 'Profil desa berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate profil desa.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DesaProfile $desaProfile)
    {
        if (!auth()->user()->can('manage-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $namaProfile = $desaProfile->nama_desa;

            // Log activity before delete
            LogAktivitas::log(
                "Menghapus profil desa: {$namaProfile}",
                'desa_profile',
                $desaProfile->id,
                $desaProfile->toArray(),
                null
            );

            $desaProfile->delete();

            DB::commit();

            return redirect()->route('admin.desa-profile.index')
                ->with('success', 'Profil desa berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.desa-profile.index')
                ->with('error', 'Terjadi kesalahan saat menghapus profil desa.');
        }
    }

    /**
     * Set profile as active
     */
    public function setActive(DesaProfile $desaProfile)
    {
        if (!auth()->user()->can('manage-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Deactivate all profiles
            DesaProfile::where('is_active', true)->update(['is_active' => false]);

            // Activate selected profile
            $desaProfile->update(['is_active' => true]);

            // Log activity
            LogAktivitas::log(
                "Mengaktifkan profil desa: {$desaProfile->nama_desa}",
                'desa_profile',
                $desaProfile->id,
                ['is_active' => false],
                ['is_active' => true]
            );

            DB::commit();

            return redirect()->route('admin.desa-profile.index')
                ->with('success', 'Profil desa berhasil diaktifkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.desa-profile.index')
                ->with('error', 'Terjadi kesalahan saat mengaktifkan profil desa.');
        }
    }

    /**
     * Export profile to PDF
     */
    public function exportPdf(DesaProfile $desaProfile)
    {
        if (!auth()->user()->can('view-desa-profile')) {
            abort(403, 'Unauthorized action.');
        }

        $data = ['profile' => $desaProfile];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.desa-profile.pdf.detail', $data);

        $filename = 'Profil_Desa_' . Str::slug($desaProfile->nama_desa) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
