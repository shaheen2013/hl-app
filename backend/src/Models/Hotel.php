<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/05/2018
 * Time: 11:19
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where($string, $string1, $array_get)
 */
class Hotel extends Model
{
    protected $table = 'hoteles';
    public $timestamps = false;
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->hotelName;
    }

    public function guid()
    {
        return $this->hasOne('App\Models\HotelGuid');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'id', 'hotel_id');
    }

}