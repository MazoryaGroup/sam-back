<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'category',
        'project_name',
        'description',
        'data',
        'title',
        'client',
        'video_upload',
        'gallery_uploads',
    ];

    protected $casts = [
        'gallery_uploads' => 'array',
    ];

    protected $appends = [
        'video_url',
        'images_url',
    ];
    protected $hidden = [
        'video_upload',
        'gallery_uploads',
    ];

    public function getVideoUrlAttribute(): ?string
    {
        return $this->video_upload
            ? Storage::disk('api_public')->url($this->video_upload)
            : null;
    }

    public function getImagesUrlAttribute(): array
    {
        return collect($this->gallery_uploads ?? [])
            ->map(fn ($path) => Storage::disk('api_public')->url($path))
            ->values()
            ->all();
    }
}
