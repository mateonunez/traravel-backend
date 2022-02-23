<?php

namespace App\Models;

use App\Models\Mood;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory, HasUuid, SoftDeletes, HasEvents;

    /** @var string */
    public const CREATED_AT = 'createdAt';

    /** @var string */
    public const UPDATED_AT = 'updatedAt';

    /** @var string */
    public const DELETED_AT = 'deletedAt';

    /** @var array */
    protected $fillable = [
        'isPublic',
        'slug',
        'name',
        'description',
        'numberOfDays',
        'numberOfNights',
    ];

    /** @var string */
    protected $table = 'travels';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function moods()
    {
        return $this->belongsToMany(Mood::class)
            ->orderBy('rating', 'desc')
            ->withPivot([
                'rating'
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tours()
    {
        return $this->hasMany(Tour::class, 'travelId');
    }

    /**
     * Compute the number of days
     *
     * @param string|mixed $startingDate
     * @param string|mixed $endingDate
     *
     * @return int
     */
    public static function computeNumberOfDays($startingDate, $endingDate): int
    {
        $startingDate = Carbon::parse($startingDate);
        $endingDate = Carbon::parse($endingDate);

        if ($endingDate->lessThan($startingDate)) {
            return -1;
        }

        return $startingDate->diffInDays($endingDate);
    }
}
