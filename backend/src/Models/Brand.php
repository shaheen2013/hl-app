<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/05/2018
 * Time: 11:19
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamps = false;


    public function hotel()
    {
        return $this->hasOne('App\Models\Hotel', 'id', 'hotel_id');
    }

    public function chain()
    {
        return $this->hasOne('App\Models\Chain', 'id', 'chain_id');
    }
}