<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/05/2018
 * Time: 11:18
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class HotelGuid extends Model
{
    protected $table = 'hotel_guid';
    public $timestamps = false;


    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel', 'id_hotel', 'id');
    }

}