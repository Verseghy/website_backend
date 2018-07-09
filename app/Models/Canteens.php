<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canteens extends Model
{
    protected $table = 'canteen_data';
    protected $fillable = ['date'];
    protected $hidden = ['created_at','updated_at'];
    protected $touches = ['menus'];
    
    public function menus()
    {
        return $this->belongsToMany('App\Models\Canteens\Menus', 'canteen_pivot_menus_data', 'data_id', 'menu_id');
    }
}
