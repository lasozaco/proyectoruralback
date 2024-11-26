<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Event
 *
 * @property $institution_id
 * @property $id
 * @property $title
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property  $
 * @property Multimedia[] $multimedia
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Event extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'institution_id',
        'title',
        'description'
    ];


    /**
     * @return BelongsTo
     */
    public function institution()
    {
        return $this->belongsTo(\App\Models\Institution::class, 'institution_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function multimedia()
    {
        return $this->hasMany(\App\Models\Multimedia::class, 'id', 'event_id');
    }

}
