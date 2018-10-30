<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Canteens extends Model
{
    use CrudTrait;
    protected $table = 'canteen_data';
    protected $fillable = ['date'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $touches = ['menus'];

    public function menus()
    {
        return $this->belongsToMany('App\Models\Canteens\Menus', 'canteen_pivot_menus_data', 'data_id', 'menu_id');
    }

    protected static function boot()
    {
        parent::boot();

        // @codeCoverageIgnoreStart
        static::deleting(function (Canteens $canteen) {
            $canteen->menus()->detach();
        });
        // @codeCoverageIgnoreEnd
    }
}
