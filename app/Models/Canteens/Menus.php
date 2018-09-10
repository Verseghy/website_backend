<?php

namespace App\Models\Canteens;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Menus extends Model
{
    use CrudTrait;
    protected $table = 'canteen_menus';
    protected $fillable = ['menu', 'type'];
    protected $hidden = ['pivot', 'created_at','updated_at'];
    //protected $touches = ['canteens'];
    
    /*public function canteens()
    {
        return $this->belongsToMany('App\Models\Canteens', 'canteen_pivot_menus_data', 'menu_id', 'data_id');
    }*/
}
