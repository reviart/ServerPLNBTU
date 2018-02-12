<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
  protected $fillable = [
      'name', 'path', 'user_id',
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

  public function folders(){
    return $this->hasMany('App\Folder');
  }
}
