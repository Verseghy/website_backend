<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Newsletter extends Model
{
    use CrudTrait;
    protected $table = 'newsletter';
    protected $fillable = ['email', 'mldata', 'token'];
    protected $hidden = ['created_at', 'updated_at'];
}
