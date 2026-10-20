<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     * Implements File Deletion Cleanup pattern:
     * Automatically removes physical storage file when document record is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (Document $document) {
            if (! empty($document->file_path)) {
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                if (Storage::disk('local')->exists($document->file_path)) {
                    Storage::disk('local')->delete($document->file_path);
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
        'document_number',
        'document_date',
        'tanggal_acara',
        'title',
        'type',
        'sub_type',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'document_date' => 'date',
        'tanggal_acara' => 'date',
        'file_size' => 'integer',
    ];

    /**
     * User who uploaded this document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human readable formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }

    /**
     * Display label for category/type including sub-type.
     */
    public function getCategoryLabelAttribute(): string
    {
        if ($this->type === 'Laporan' && $this->sub_type) {
            return "Laporan ({$this->sub_type})";
        }

        return $this->type;
    }
}
