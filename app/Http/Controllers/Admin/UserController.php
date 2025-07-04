<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogAktivitas;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-users');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Apply filters
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($validated['role']);

            // Log activity
            LogAktivitas::log(
                "Menambahkan user baru: {$user->name} dengan role {$validated['role']}",
                'users',
                $user->id,
                null,
                $user->toArray()
            );

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan user.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'kegiatan', 'realisasi', 'logAktivitas' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        DB::beginTransaction();
        try {
            $oldData = $user->toArray();
            $oldRole = $user->getRoleNames()->first();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($validated['password']) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update role if changed
            if ($oldRole !== $validated['role']) {
                $user->syncRoles([$validated['role']]);
            }

            // Log activity
            LogAktivitas::log(
                "Mengubah user: {$user->name}" . ($oldRole !== $validated['role'] ? " (role: {$oldRole} → {$validated['role']})" : ""),
                'users',
                $user->id,
                $oldData,
                $user->fresh()->toArray()
            );

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui user.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            $userRole = $user->getRoleNames()->first();

            // Log activity before delete
            LogAktivitas::log(
                "Menghapus user: {$userName} (role: {$userRole})",
                'users',
                $user->id,
                $user->toArray(),
                null
            );

            $user->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }
}
