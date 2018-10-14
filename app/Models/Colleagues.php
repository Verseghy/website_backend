<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Colleagues extends Model
{
    use CrudTrait;
    protected $table = 'colleagues_data';
    public $timestamps = false;
    protected $fillable = ['name','jobs','subjects','roles','awards','image','category']; 
    protected $hidden = [];                                                      
    //Follows the format of the old website. For example jobs=igazgató|subjects=matematika,technika
    //|roles:"A Verseghy Diákokért" Alapítvány kuratóriumának elnöke,|awards=Graphisoft-díj (2002.)
}
