<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use Searchable;
    
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'UG',
        'MSc',
        'experimental',
        'computational',
        'hidden',
        'selected_user_id',
        'selected_user2_id',
        'popularity'
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
        return $this->hasMany('App\Like', 'likeable_id');
    }

    public function getUsersLiked()
    {
        return $this->morphToMany('App\User', 'likeable')->whereDeletedAt(null);
    }

    public function getIsLikedAttribute()
    {
        $like = $this->likes()->whereUserId(Auth::id())->first();
        return (!is_null($like)) ? true : false;
    }

    public function getSelectedUser()
    {
        return $this->belongsTo('App\User', 'selected_user_id');
    }

    public function getSelectedUser2()
    {
        return $this->belongsTo('App\User', 'selected_user2_id');
    }

    public function getIsSelectedAttribute()
    {
        if ($this->selected_user_id != 0 || $this->selected_user2_id != 0) {
            $value = 1;
        } else {
            $value = 0;
        }
        return $value;
    }
}
