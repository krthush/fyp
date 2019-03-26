<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use Searchable;
    
    protected $fillable = ['user_id',
        'title',
        'description',
        'UG',
        'MSc',
        'ME4',
        'experimental',
        'computational',
        'hidden',
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
    */

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['author_name'] = $this->user()->first()->name;

        return $array;
    }

    public function user()
	{
		return $this->belongsTo(User::class);
	}

    public function likes()
    {
        return $this->morphToMany('App\User', 'likeable')->whereDeletedAt(null);
    }

    public function getIsLikedAttribute()
    {
        $like = $this->likes()->whereUserId(Auth::id())->first();
        return (!is_null($like)) ? true : false;
    }
}
