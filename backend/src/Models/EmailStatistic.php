<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailStatistic extends Model
{
    protected $connection = 'statistics_db';
    protected $table = 'Fact_emails';
    public $timestamps = false;
}