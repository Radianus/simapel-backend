<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
        'type',
    ];

    // Accessor untuk mendapatkan nilai sebagai boolean jika tipe adalah 'checkbox'
    public function getBoolValueAttribute()
    {
        return (bool) $this->value;
    }
}
