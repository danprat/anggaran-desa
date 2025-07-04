<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use App\Models\User;

class LogAktivitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-log');
    }

    /**
     * Display a listing of log aktivitas
     */
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('tabel_terkait')) {
            $query->byTable($request->tabel_terkait);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->byDateRange($request->tanggal_mulai, $request->tanggal_selesai . ' 23:59:59');
        }

        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', '%' . $request->search . '%');
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $users = User::orderBy('name')->get();
        $tables = LogAktivitas::distinct()->pluck('tabel_terkait')->filter()->sort();

        return view('log.index', compact('logs', 'users', 'tables'));
    }

    /**
     * Show the specified log
     */
    public function show(LogAktivitas $log)
    {
        $log->load('user');

        return view('log.show', compact('log'));
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        $this->authorize('export', 'log');

        $query = LogAktivitas::with('user');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('tabel_terkait')) {
            $query->byTable($request->tabel_terkait);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->byDateRange($request->tanggal_mulai, $request->tanggal_selesai . ' 23:59:59');
        }

        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', '%' . $request->search . '%');
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'log_aktivitas_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, [
                'Tanggal',
                'User',
                'Aktivitas',
                'Tabel Terkait',
                'Row ID',
                'IP Address',
                'User Agent'
            ]);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->user->name,
                    $log->aktivitas,
                    $log->tabel_terkait ?? '-',
                    $log->row_id ?? '-',
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
