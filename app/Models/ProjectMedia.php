<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectMedia extends Model
{
    use HasFactory;

    protected $table = 'project_media';

    protected $fillable = [
        'project_id',
        'file_path',
        'file_name',
        'file_type',
        'media_type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getDisplayUrlAttribute()
    {
        if ($this->media_type === 'foto' && $this->is_public) {
            // asset('storage/' . $this->file_path) akan menghasilkan URL lengkap
            // seperti http://127.0.0.1:8000/storage/project_media/photos/namafile.jpg
            // Kita mengandalkan symlink public/storage => storage/app/public
            // dan konfigurasi Laragon/webserver untuk mengarahkan ke file fisik
            return asset('storage/' . $this->file_path);
        } else {
            return route('media.download', ['id' => $this->id, 'filename' => $this->file_name]);
        }
    }
    // Pastikan media terhapus dari storage saat record dihapus dari database
    protected static function booted()
    {
        static::deleting(function ($media) {
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
        });
    }
}
