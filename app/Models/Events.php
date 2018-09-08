<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Events extends Model
{
    use CrudTrait;
    protected $table = 'events_data';
    protected $fillable = ['date_from', 'date_to', 'title', 'description', 'color'];
    protected $hidden = ['created_at','updated_at'];
}
