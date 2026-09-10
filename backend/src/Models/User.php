<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $table = 'users';
  public $timestamps = false;


  public function getNameAttribute()
  {
    return $this->nombre;
  }

  public function guid()
  {
      return $this->hasOne('App\Models\UserGuid');
  }

}