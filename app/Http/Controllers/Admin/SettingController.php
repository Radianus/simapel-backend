<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting; // Import model Setting
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage; // Untuk upload file jika nanti ada setting logo

class SettingController extends Controller
{
    // Untuk saat ini, kita tidak akan menerapkan middleware permission di sini
    // agar admin@simapel.com bisa mengelola setting tanpa masalah izin.
    // public function __construct()
    // {
    //     $this->middleware('permission:manage settings'); // Contoh izin baru
    // }

    /**
     * Menampilkan form pengaturan sistem.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua pengaturan dari database
        $settings = Setting::all()->keyBy('key'); // Mengambil sebagai koleksi yang diindeks berdasarkan 'key'

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Memperbarui pengaturan sistem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            // Validasi data yang masuk
            $rules = [
                'app_name_display' => 'required|string|max:255',
                'app_slogan' => 'nullable|string|max:255',
                'contact_email_admin' => 'required|email|max:255',
                'default_map_latitude' => 'required|numeric|between:-90,90',
                'default_map_longitude' => 'required|numeric|between:-180,180',
                'app_logo_url' => 'nullable|string', // Untuk URL logo, jika nanti ada upload akan diganti
                'show_public_dashboard' => 'nullable|boolean',
            ];

            // Jika ada input file logo nanti, tambahkan validasi di sini
            // if ($request->hasFile('app_logo')) {
            //     $rules['app_logo'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            // }

            $validatedData = $request->validate($rules);

            foreach ($validatedData as $key => $value) {
                // Penanganan khusus untuk checkbox
                if ($key === 'show_public_dashboard') {
                    $value = $request->boolean('show_public_dashboard');
                }

                $setting = Setting::where('key', $key)->first();
                if ($setting) {
                    $setting->value = $value;
                    $setting->save();
                }
            }

            // Jika ada upload file logo, contoh:
            // if ($request->hasFile('app_logo')) {
            //     $settingLogo = Setting::where('key', 'app_logo_url')->first();
            //     if ($settingLogo) {
            //         // Hapus logo lama jika ada
            //         if ($settingLogo->value && filter_var($settingLogo->value, FILTER_VALIDATE_URL) === FALSE && Storage::disk('public')->exists($settingLogo->value)) {
            //             Storage::disk('public')->delete($settingLogo->value);
            //         }
            //         $path = $request->file('app_logo')->store('public/settings', 'public');
            //         $settingLogo->value = $path;
            //         $settingLogo->save();
            //     }
            // }

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage())->withInput();
        }
    }
}
