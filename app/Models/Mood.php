<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mood extends Model
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
        'name',
        'description',
        'emoji'
    ];
}
