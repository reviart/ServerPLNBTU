<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*protected $dates = [
      'last_sign_in', 'current_sign_in'
    ];

    public function getCurrentSignInAttribute()
    {
        return Carbon::parse($this->attributes['current_sign_in'])
           ->format('d, M Y H:i');
    }*/

    public function bidangs(){
      return $this->hasMany('App\Bidang');
    }

    public function folders(){
      return $this->hasMany('App\Folder');
    }

    public function files(){
      return $this->hasMany('App\File');
    }
}
