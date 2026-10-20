<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Layanan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'layanans';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'title',
        'badge',
        'badge_color',
        'icon',
        'description',
        'steps',
        'requirements_penjual',
        'requirements_pembeli',
        'estimated_time',
        'order_num',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'steps' => 'array',
            'requirements_penjual' => 'array',
            'requirements_pembeli' => 'array',
            'order_num' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Automatically generate unique slug when empty.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Layanan $layanan) {
            if (empty($layanan->slug)) {
                $baseSlug = Str::slug($layanan->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }
                $layanan->slug = $slug;
            }
        });
    }

    /**
     * Convert textarea line-by-line input to clean array of non-empty strings.
     */
    public static function linesToArray(?string $text): array
    {
        if (blank($text)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);

        return array_values(array_filter(array_map('trim', $lines), fn ($line) => ! empty($line)));
    }

    /**
     * Convert array to string of lines for form textarea editing.
     */
    public function arrayToLines(string $field): string
    {
        $arr = $this->{$field} ?? [];
        if (! is_array($arr)) {
            return '';
        }

        return implode("\n", $arr);
    }
}
