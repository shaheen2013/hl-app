<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/05/2018
 * Time: 11:23
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHotel extends Model
{
    protected $table = 'user_hotels';
    public $timestamps = false;

    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel', 'id_hotel', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_usuario', 'id');
    }
}