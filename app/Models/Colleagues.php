<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Colleagues extends Model
{
    use CrudTrait;
    protected $table = 'colleagues_data';
    public $timestamps = false;
    protected $fillable = ['name', 'jobs', 'subjects', 'roles', 'awards', 'image', 'category'];
    protected $hidden = [];
    //Follows the format of the old website. For example jobs=igazgató|subjects=matematika,technika
    //|roles:"A Verseghy Diákokért" Alapítvány kuratóriumának elnöke,|awards=Graphisoft-díj (2002.)

    public function setImageAttribute($value)
    {
        $attribute_name = 'image';
        $disk = 'colleagues_images';
        $destination_path = '/';

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public static function boot()
    {
        parent::boot();

        // @codeCoverageIgnoreStart
        static::deleting(function (Colleagues $coll) {
            if (isset($coll->image)) {
                \Storage::disk('colleagues_images')->delete($coll->image);
            }
        });
        // @codeCoverageIgnoreEnd
    }
}
