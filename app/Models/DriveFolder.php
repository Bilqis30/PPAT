<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriveFolder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'user_id',
        'name',
        'description',
        'color',
    ];

    /**
     * Parent folder relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Subfolders relationship.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name', 'asc');
    }

    /**
     * Direct files relationship.
     */
    public function files(): HasMany
    {
        return $this->hasMany(DriveFile::class, 'folder_id')->orderBy('name', 'asc');
    }

    /**
     * Owner user relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get ancestors list for breadcrumb navigation.
     *
     * @return array<int, DriveFolder>
     */
    public function getAncestors(): array
    {
        $ancestors = [];
        $current = $this->parent;

        while ($current !== null) {
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }

        return $ancestors;
    }
}
