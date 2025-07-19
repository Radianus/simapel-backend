<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\Middleware; // Tambahkan ini
use Illuminate\Routing\Controllers\HasMiddleware; // Tambahkan ini
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel; // Tambahkan ini
use App\Exports\ProjectsExport; // Tambahkan ini
class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::orderBy('created_at', 'desc');

        // Logika Pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('description', 'ILIKE', '%' . $search . '%')
                    ->orWhere('responsible_agency', 'ILIKE', '%' . $search . '%');
            });
        }

        // Logika Filter Status
        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
        }

        // --- LOGIKA FILTER SEKTOR (SEKARANG DISIAPKAN DI CONTROLLER) ---
        // Ambil daftar sektor unik dari semua proyek yang ada
        $availableSectors = Project::select('sector')
            ->distinct()
            ->pluck('sector')
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        if ($request->filled('sector_filter')) {
            $query->where('sector', $request->input('sector_filter'));
        }
        // --- AKHIR LOGIKA FILTER SEKTOR ---

        // LOGIKA FILTER TAHUN (SUDAH ADA)
        $selectedYear = $request->input('year_filter');
        $yearsFromStartDate = Project::whereNotNull('start_date')
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM start_date) as year')
            ->pluck('year');
        $yearsFromEndDate = Project::whereNotNull('end_date')
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM end_date) as year')
            ->pluck('year');
        $availableYears = $yearsFromStartDate->merge($yearsFromEndDate)
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
        rsort($availableYears);

        if ($request->filled('year_filter')) {
            $query->where(function ($q) use ($selectedYear) {
                $q->whereYear('start_date', $selectedYear)
                    ->orWhereYear('end_date', $selectedYear);
            });
        }
        // AKHIR LOGIKA FILTER TAHUN

        // Ambil proyek dengan paginasi
        $projects = $query->paginate(10)->withQueryString();

        // Kirim semua variabel yang dibutuhkan ke view
        return view('admin.projects.index', compact('projects', 'availableYears', 'selectedYear', 'availableSectors')); // Tambahkan 'availableSectors'
    }


    /**
     * Menampilkan form untuk membuat proyek baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Menyimpan proyek baru ke database dari form admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'responsible_agency' => 'nullable|string|max:255',
                'sector' => 'nullable|string|max:255',
                'budget' => 'nullable|numeric',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'description' => 'nullable|string',
                'progress_percentage' => 'nullable|numeric|min:0|max:100',
                'status' => 'nullable|string|in:On-Track,Terlambat,Selesai',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
            ]);

            $project = Project::create([
                'name' => $validatedData['name'],
                'responsible_agency' => $validatedData['responsible_agency'] ?? null,
                'sector' => $validatedData['sector'] ?? null,
                'budget' => $validatedData['budget'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'description' => $validatedData['description'] ?? null,
                'progress_percentage' => $validatedData['progress_percentage'],
                'status' => $validatedData['status'],
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
            ]);

            // Simpan foto
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = Storage::disk('public')->putFile('project_media/photos', $photo);
                    ProjectMedia::create([
                        'project_id' => $project->id,
                        'file_path' => $path, // path ini akan jadi project_media/photos/namafile.jpg
                        'file_name' => $photo->getClientOriginalName(),
                        'file_type' => $photo->getClientMimeType(),
                        'media_type' => 'foto',
                        'description' => 'Foto progres proyek ' . $project->name,
                        'is_public' => true,
                    ]);
                }
            }

            // Simpan dokumen
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    // PERBAIKAN DI SINI: Hapus 'public/' dari path pertama
                    $path = Storage::disk('public')->putFile('project_media/documents', $document);
                    ProjectMedia::create([
                        'project_id' => $project->id,
                        'file_path' => $path,
                        'file_name' => $document->getClientOriginalName(),
                        'file_type' => $document->getClientMimeType(),
                        'media_type' => 'dokumen',
                        'description' => 'Dokumen proyek ' . $project->name,
                        'is_public' => false,
                    ]);
                }
            }
            return redirect()->route('admin.projects.index')->with('success', 'Proyek dan media berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create project: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Project $project)
    {
        $project->load('media');
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Memperbarui proyek yang sudah ada di database dari form admin.
     * ...
     */
    public function update(Request $request, Project $project)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'responsible_agency' => 'nullable|string|max:255',
                'sector' => 'nullable|string|max:255',
                'budget' => 'nullable|numeric',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'description' => 'nullable|string',
                'progress_percentage' => 'nullable|numeric|min:0|max:100',
                'status' => 'nullable|string|in:On-Track,Terlambat,Selesai',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'documents.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
                'delete_media_ids' => 'nullable|array',
                'delete_media_ids.*' => 'exists:project_media,id',
            ]);

            $project->update([
                'name' => $validatedData['name'],
                'responsible_agency' => $validatedData['responsible_agency'] ?? $project->responsible_agency,
                'sector' => $validatedData['sector'] ?? $project->sector,
                'budget' => $validatedData['budget'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'description' => $validatedData['description'] ?? $project->description,
                'progress_percentage' => $validatedData['progress_percentage'],
                'status' => $validatedData['status'],
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
            ]);

            if (isset($validatedData['delete_media_ids']) && is_array($validatedData['delete_media_ids'])) {
                foreach ($validatedData['delete_media_ids'] as $mediaId) {
                    $mediaItem = ProjectMedia::find($mediaId);
                    if ($mediaItem) {
                        if (Storage::disk('public')->exists($mediaItem->file_path)) {
                            Storage::disk('public')->delete($mediaItem->file_path);
                        }
                        $mediaItem->delete();
                    }
                }
            }

            // Simpan foto baru
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = Storage::disk('public')->putFile('project_media/photos', $photo); // Perbaikan
                    ProjectMedia::create([
                        'project_id' => $project->id,
                        'file_path' => $path,
                        'file_name' => $photo->getClientOriginalName(),
                        'file_type' => $photo->getClientMimeType(),
                        'media_type' => 'foto',
                        'description' => 'Foto progres proyek ' . $project->name,
                        'is_public' => true,
                    ]);
                }
            }

            // Simpan dokumen baru
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $document) {
                    $path = Storage::disk('public')->putFile('project_media/documents', $document); // Perbaikan
                    ProjectMedia::create([
                        'project_id' => $project->id,
                        'file_path' => $path,
                        'file_name' => $document->getClientOriginalName(),
                        'file_type' => $document->getClientMimeType(),
                        'media_type' => 'dokumen',
                        'description' => 'Dokumen proyek ' . $project->name,
                        'is_public' => false,
                    ]);
                }
            }


            return redirect()->route('admin.projects.index')->with('success', 'Proyek dan media berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update project: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified project from storage.
     * ...
     */
    public function destroy(Project $project)
    {
        try {
            foreach ($project->media as $mediaItem) {
                if (Storage::disk('public')->exists($mediaItem->file_path)) {
                    Storage::disk('public')->delete($mediaItem->file_path);
                }
            }
            $project->delete();
            return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus proyek: ' . $e->getMessage());
        }
    }


    /**
     * Export data proyek ke file Excel.
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        // Untuk saat ini, kita akan mengekspor semua data proyek.
        // Anda bisa menambahkan logika filter di sini juga jika ingin mengekspor data yang sudah difilter dari halaman index.
        // Contoh:
        // $query = Project::query();
        // if (request()->filled('status_filter')) {
        //     $query->where('status', request()->input('status_filter'));
        // }
        // return Excel::download(new ProjectsExport($query->get()), 'data_proyek_simapel.xlsx');

        return Excel::download(new ProjectsExport, 'data_proyek_simapel_' . date('Ymd_His') . '.xlsx');
    }
}