<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'type',
        'mime_type',
        'size',
        'alt_text',
    ];

    public function getUrlAttribute()
    {
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_path);
    }
}
