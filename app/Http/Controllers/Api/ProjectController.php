<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project; // Pastikan model Project di-import
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * Mengambil daftar semua proyek.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Mengambil semua proyek dari database menggunakan Eloquent
            $projects = Project::all();

            return response()->json([
                'message' => 'Projects retrieved successfully',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            // Menangani error jika terjadi masalah saat mengambil data
            return response()->json([
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage()
            ], 500); // Kode status HTTP 500 untuk Internal Server Error
        }
    }

    /**
     * Menyimpan proyek baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang masuk dari request.
            // Ini memastikan data yang disimpan sesuai format yang diharapkan.
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'responsible_agency' => 'nullable|string|max:255',
                'sector' => 'nullable|string|max:255',
                'budget' => 'nullable|numeric',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date', // Tanggal selesai harus setelah atau sama dengan tanggal mulai
                'description' => 'nullable|string',
                'progress_percentage' => 'nullable|numeric|min:0|max:100', // Progres harus antara 0 dan 100%
                'status' => 'nullable|string|in:On-Track,Terlambat,Selesai', // Hanya nilai yang ditentukan yang diizinkan
                'latitude' => 'required|numeric|between:-90,90', // Latitude harus angka antara -90 dan 90
                'longitude' => 'required|numeric|between:-180,180', // Longitude harus angka antara -180 dan 180
            ]);

            // Membuat entri proyek baru di database menggunakan Eloquent
            $project = Project::create([
                'name' => $validatedData['name'],
                'responsible_agency' => $validatedData['responsible_agency'] ?? null,
                'sector' => $validatedData['sector'] ?? null,
                'budget' => $validatedData['budget'] ?? null,
                'start_date' => $validatedData['start_date'] ?? null,
                'end_date' => $validatedData['end_date'] ?? null,
                'description' => $validatedData['description'] ?? null,
                'progress_percentage' => $validatedData['progress_percentage'] ?? 0,
                'status' => $validatedData['status'] ?? 'On-Track',
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
            ]);

            return response()->json([
                'message' => 'Project created successfully',
                'data' => $project // Mengembalikan data proyek yang baru dibuat
            ], 201); // Kode status HTTP 201 untuk Created
        } catch (ValidationException $e) {
            // Menangani error validasi (misal, data yang dikirim tidak sesuai aturan)
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // Kode status HTTP 422 untuk Unprocessable Entity
        } catch (\Exception $e) {
            // Menangani error umum lainnya saat proses penyimpanan
            return response()->json([
                'message' => 'Failed to create project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail satu proyek spesifik.
     *
     * @param  \App\Models\Project  $project (Laravel akan otomatis menemukan proyek berdasarkan ID di URL)
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project)
    {
        try {
            // Laravel Route Model Binding secara otomatis telah menemukan dan mengisi objek $project
            return response()->json([
                'message' => 'Project retrieved successfully',
                'data' => $project // Data proyek sudah mengandung latitude dan longitude
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memperbarui informasi proyek yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project)
    {
        try {
            // Validasi data yang masuk untuk update
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
            ]);

            // Perbarui data proyek di database menggunakan Eloquent
            $project->update([
                'name' => $validatedData['name'],
                'responsible_agency' => $validatedData['responsible_agency'] ?? $project->responsible_agency,
                'sector' => $validatedData['sector'] ?? $project->sector,
                'budget' => $validatedData['budget'] ?? $project->budget,
                'start_date' => $validatedData['start_date'] ?? $project->start_date,
                'end_date' => $validatedData['end_date'] ?? $project->end_date,
                'description' => $validatedData['description'] ?? $project->description,
                'progress_percentage' => $validatedData['progress_percentage'] ?? $project->progress_percentage,
                'status' => $validatedData['status'] ?? $project->status,
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
            ]);

            return response()->json([
                'message' => 'Project updated successfully',
                'data' => $project
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus proyek dari database.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete(); // Hapus proyek dari database menggunakan Eloquent
            return response()->json(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete project',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
