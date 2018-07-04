<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'events_data';
    public $timestamps = false;
    protected $fillable = ['date_from', 'date_to', 'title', 'description', 'color'];
}
