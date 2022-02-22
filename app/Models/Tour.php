<?php

namespace App\Models;

use App\Models\Travel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tour extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    /** @var string */
    public const CREATED_AT = 'createdAt';

    /** @var string */
    public const UPDATED_AT = 'updatedAt';

    /** @var string */
    public const DELETED_AT = 'deletedAt';

    /** @var array */
    protected $fillable = [
        'travelId',
        'name',
        'description',
        'startingDate',
        'endingDate',
        'price'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    /**
     * Casting the price
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function price(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value / 100,
        );
    }
}
