<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DriveFile extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     * Implements File Deletion Cleanup pattern:
     * Automatically removes physical storage file when drive file record is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (DriveFile $file) {
            if (! empty($file->file_path)) {
                if (Storage::disk('local')->exists($file->file_path)) {
                    Storage::disk('local')->delete($file->file_path);
                }
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'folder_id',
        'user_id',
        'name',
        'file_path',
        'file_size',
        'mime_type',
        'extension',
        'relative_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Folder relationship.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(DriveFolder::class, 'folder_id');
    }

    /**
     * Owner user relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Human-readable file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }

    /**
     * Check if file is an image.
     */
    public function getIsImageAttribute(): bool
    {
        $ext = strtolower($this->extension ?? pathinfo($this->name, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    /**
     * Check if file is a PDF.
     */
    public function getIsPdfAttribute(): bool
    {
        $ext = strtolower($this->extension ?? pathinfo($this->name, PATHINFO_EXTENSION));

        return $ext === 'pdf';
    }

    /**
     * Determine Lucide icon for file type.
     */
    public function getIconAttribute(): string
    {
        $ext = strtolower($this->extension ?? pathinfo($this->name, PATHINFO_EXTENSION));

        return match ($ext) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file-edit',
            'xls', 'xlsx', 'csv' => 'file-spreadsheet',
            'ppt', 'pptx' => 'presentation',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' => 'image',
            'zip', 'rar', '7z', 'tar', 'gz' => 'file-archive',
            'mp3', 'wav', 'ogg' => 'music',
            'mp4', 'mkv', 'avi', 'mov' => 'video',
            default => 'file',
        };
    }
}
