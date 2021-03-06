<?php

namespace App\Models\Canteens;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Menus extends Model
{
    use CrudTrait;
    protected $table = 'canteen_menus';
    protected $fillable = ['menu', 'type'];
    protected $hidden = ['pivot', 'created_at', 'updated_at'];
    //protected $touches = ['canteens'];

    /*public function canteens()
    {
        return $this->belongsToMany('App\Models\Canteens', 'canteen_pivot_menus_data', 'menu_id', 'data_id');
    }*/

    protected static function boot()
    {
        parent::boot();

        // @codeCoverageIgnoreStart
        static::deleting(function (Menus $menu) {
            $menu->belongsToMany('App\Models\Canteens', 'canteen_pivot_menus_data', 'menu_id', 'data_id')->detach();
        });
        // @codeCoverageIgnoreEnd
    }
}
