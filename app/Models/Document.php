<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'document_type',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'verification_status',
        'remarks',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }
        return Storage::disk('public')->url($this->file_path);
    }
}
