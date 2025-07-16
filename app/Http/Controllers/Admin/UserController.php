<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import model User
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash; // Untuk hash password
use Spatie\Permission\Models\Role; // Import model Role dari Spatie

class UserController extends Controller
{
    // Untuk saat ini, kita tidak akan menerapkan middleware permission di sini
    // agar super_admin bisa mengelola user. Jika nanti ada kebutuhan,
    // bisa tambahkan di web.php atau di sini setelah modul manajemen peran/izin visual.
    // public function __construct()
    // {
    //     $this->middleware('permission:manage users');
    // }

    /**
     * Menampilkan daftar semua pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10); // Load relasi peran
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all(); // Ambil semua peran yang ada
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email', // Email harus unik
                'password' => 'required|string|min:8|confirmed', // Password minimal 8 karakter dan konfirmasi
                'roles' => 'nullable|array', // Peran yang dipilih (bisa lebih dari satu)
                'roles.*' => 'exists:roles,name', // Pastikan peran yang dipilih ada di tabel roles
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Hash password sebelum disimpan
            ]);

            // Berikan peran kepada pengguna baru
            if ($request->has('roles')) {
                $user->syncRoles($validatedData['roles']); // syncRoles akan mengganti semua peran yang ada dengan yang baru
            } else {
                $user->syncRoles([]); // Jika tidak ada peran dipilih, hapus semua peran
            }

            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit pengguna yang sudah ada.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all(); // Ambil semua peran
        $userRoles = $user->roles->pluck('name')->toArray(); // Ambil peran yang dimiliki user saat ini
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Memperbarui pengguna yang sudah ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Email harus unik kecuali untuk user itu sendiri
                'password' => 'nullable|string|min:8|confirmed', // Password opsional, hanya jika ingin diubah
                'roles' => 'nullable|array',
                'roles.*' => 'exists:roles,name',
            ]);

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            if ($request->filled('password')) { // Jika password diisi, hash dan update
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();

            // Perbarui peran pengguna
            if ($request->has('roles')) {
                $user->syncRoles($validatedData['roles']);
            } else {
                $user->syncRoles([]);
            }

            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus pengguna dari database.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            // Pastikan tidak menghapus diri sendiri
            if (auth()->id() == $user->id) {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            }
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
