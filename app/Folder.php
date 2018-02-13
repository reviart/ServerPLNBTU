<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
  protected $fillable = [
      'name', 'path', 'access_permission', 'user_id', 'bidang_id'
  ];

  public function getCreatedAtAttribute()
  {
      return Carbon::parse($this->attributes['created_at'])
         ->format('d, M Y H:i');
  }

  public function getUpdatedAtAttribute()
  {
      return Carbon::parse($this->attributes['updated_at'])
         ->format('d, M Y H:i');
  }

  public function user(){
    return $this->belongsTo('App\User');
  }

  public function bidang(){
    return $this->belongsTo('App\Bidang');
  }

  public function files(){
    return $this->hasMany('App\File');
  }
}
