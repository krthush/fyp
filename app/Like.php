<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// app/Like.php
class Like extends Model
{
    use SoftDeletes;

    protected $table = 'likeables';

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
        'order_column',
    ];

    /**
     * Get all of the projects that are assigned this like.
     */
    public function projects()
    {
        return $this->morphedByMany('App\Project', 'likeable');
    }
}