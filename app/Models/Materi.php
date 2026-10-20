<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Materi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'materis';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category',
        'title',
        'slug',
        'cover_image',
        'excerpt',
        'content',
        'published_at',
        'author_name',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }

    /**
     * Automatically generate unique slug when setting title if slug is empty.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Materi $materi) {
            if (empty($materi->slug)) {
                $baseSlug = Str::slug($materi->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }
                $materi->slug = $slug;
            }
        });
    }

    /**
     * Relation to author user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Indonesian day name mapping.
     */
    public static array $daysIndonesian = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    /**
     * Indonesian month name mapping.
     */
    public static array $monthsIndonesian = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Get formatted Indonesian date e.g. "Rabu, 02 September 2026"
     */
    public function getFormattedDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at ?? Carbon::now();
        $englishDay = $date->format('l');
        $dayName = self::$daysIndonesian[$englishDay] ?? $englishDay;
        $dayNum = $date->format('d');
        $monthName = self::$monthsIndonesian[(int) $date->format('n')] ?? $date->format('F');
        $year = $date->format('Y');

        return "{$dayName}, {$dayNum} {$monthName} {$year}";
    }

    /**
     * Resolve public URL for cover image.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if (! $this->cover_image) {
            return asset('images/materi/batanghari_sembilan.jpg');
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        if (str_starts_with($this->cover_image, 'images/')) {
            return asset($this->cover_image);
        }

        return asset('storage/'.ltrim($this->cover_image, '/'));
    }
}
