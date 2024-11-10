<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Multimedia
 *
 * @property $event_id
 * @property $id
 * @property $url
 * @property $type
 * @property $created_at
 * @property $updated_at
 *
 * @property Event $event
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Multimedia extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['event_id', 'url', 'type'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class, 'event_id', 'id');
    }
    
}
