<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'staff',
        'year',
        'admin',
        'superadmin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function likedProjects()
    {
        return $this->morphedByMany('App\Project', 'likeable')->whereDeletedAt(null)->orderBy('order_column');
    }

    public function isSelected()
    {
        $selectedProject = Project::where('selected_user_id', $this->id)->first();

        $selectedProject2 = Project::where('selected_user2_id', $this->id)->first();

        return (!is_null($selectedProject) || !is_null($selectedProject2)) ? true : false;
    }
}
