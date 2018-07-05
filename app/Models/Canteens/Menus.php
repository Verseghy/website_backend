<?php

namespace App\Models\Canteens;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = 'canteen_menus';
    public $timestamps = false;
    protected $fillable = ['menu', 'type'];
    
    public function canteens()
    {
        return $this->belongsToMany('App\Models\Canteens', 'canteen_pivot_menus_data', 'menu_id', 'data_id');
    }
}
