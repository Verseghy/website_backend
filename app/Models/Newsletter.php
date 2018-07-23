<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletter';
    protected $fillable = ['email', 'mldata', 'token'];
    protected $hidden = ['created_at','updated_at'];
}
