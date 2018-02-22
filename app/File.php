<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
  protected $fillable = [
    'name', 'ext', 'size', 'access_permission', 'user_id', 'bidang_id', 'folder_id'
  ];

  public function user(){
    return $this->belongsTo('App\User');
  }

  public function bidang(){
    return $this->belongsTo('App\Bidang');
  }

  public function folder(){
    return $this->belongsTo('App\Folder');
  }
}
