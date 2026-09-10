<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/05/2018
 * Time: 11:23
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    protected $table = 'cadena';
    public $timestamps = false;

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'id', 'chain_id');
    }
}
