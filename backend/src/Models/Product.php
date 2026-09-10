<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/05/2018
 * Time: 11:19
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->attributes['producto'];
    }
    public function hotels()
    {
        return $this->belongsToMany('App\Hotel', 'hotel_products', 'products_id', 'hotels_id');
    }
}