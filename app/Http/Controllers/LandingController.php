<?php

namespace App\Http\Controllers;

use App\Models\DesaProfile;
use App\Models\Kegiatan;
use App\Models\TahunAnggaran;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        $desaProfile = DesaProfile::getActive();
        $tahunAktif = TahunAnggaran::getTahunAktif();
        
        // Get some basic statistics if available
        $stats = [];
        if ($tahunAktif) {
            $stats = [
                'total_kegiatan' => Kegiatan::where('tahun_id', $tahunAktif->id)->count(),
                'kegiatan_approved' => Kegiatan::where('tahun_id', $tahunAktif->id)
                    ->where('status', 'disetujui')->count(),
                'total_pagu' => Kegiatan::where('tahun_id', $tahunAktif->id)
                    ->where('status', 'disetujui')
                    ->sum('pagu_anggaran'),
            ];
        }
        
        return view('landing', compact('desaProfile', 'tahunAktif', 'stats'));
    }
}
