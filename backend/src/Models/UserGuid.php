<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGuid extends Model
{
  protected $table = 'user_guid';
  public $timestamps = false;


  public function user()
  {
      return $this->belongsTo('App\Models\User', 'id_usuario', 'id');
  }

}