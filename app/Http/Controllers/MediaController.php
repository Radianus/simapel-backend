<?php

namespace App\Http\Controllers;

use App\Models\ProjectMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Pastikan ini ada
use Illuminate\Support\Facades\Config; // Tambahkan ini

class MediaController extends Controller
{
    public function download(Request $request, $id, $filename)
    {
        try {
            $mediaItem = ProjectMedia::findOrFail($id);

            // --- DEBUGGING INFORMASI KRUSIAL ---
            $diskRoot = Config::get('filesystems.disks.public.root');
            $fullPathExpected = $diskRoot . '/' . $mediaItem->file_path;

            // Log::info('MediaController Debug: Attempting to download file.');
            // Log::info('MediaController Debug: Media ID: ' . $mediaItem->id);
            // Log::info('MediaController Debug: File Path from DB: ' . $mediaItem->file_path);
            // Log::info('MediaController Debug: Disk Public Root: ' . $diskRoot);
            // Log::info('MediaController Debug: Full Path Expected: ' . $fullPathExpected);
            // --- END DEBUGGING INFORMASI ---

            // Cek apakah file ini disetel sebagai publik. Jika iya, langsung sajikan.
            if ($mediaItem->media_type === 'foto' && $mediaItem->is_public) {
                $path = $mediaItem->file_path;
                if (Storage::disk('public')->exists($path)) {
                    Log::info('MediaController Debug: Serving public photo directly: ' . $path);
                    return Storage::disk('public')->response($path, $mediaItem->file_name);
                }
                Log::warning("MediaController: Public photo not found at expected path during download attempt: " . $fullPathExpected);
                abort(404, 'File foto tidak ditemukan (public).');
            }

            // Untuk dokumen atau foto tidak publik
            if (!auth()->check()) {
                Log::warning("MediaController: Unauthorized download attempt for media ID " . $id . " (user not logged in).");
                abort(403, 'Anda harus login untuk mengakses file ini.');
            }

            $path = $mediaItem->file_path; // Path ini relatif ke root disk 'public'

            if (!Storage::disk('public')->exists($path)) {
                Log::error("MediaController: File not found in public storage for ID " . $id . ". Expected: " . $fullPathExpected);
                abort(404, 'File tidak ditemukan di penyimpanan.');
            }

            Log::info("MediaController: Successfully serving file ID " . $id . ": " . $fullPathExpected);
            return Storage::disk('public')->download($path, $mediaItem->file_name, [
                'Content-Type' => $mediaItem->file_type,
            ]);
        } catch (\Exception $e) {
            Log::error("MediaController: Error downloading file ID " . $id . ": " . $e->getMessage() . " at " . $e->getFile() . " line " . $e->getLine());
            abort(500, 'Terjadi kesalahan saat mengunduh file.');
        }
    }
}
