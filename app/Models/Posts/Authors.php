<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Authors extends Model
{
    use CrudTrait;
    protected $table = 'posts_authors';
    public $timestamps = false;
    protected $fillable = ['name', 'description', 'image'];
    protected $hidden = [];

    public function posts()
    {
        return $this->hasMany('App\Models\Posts','id', 'author_id');
    }
    
    
    public function setImageAttribute($value)
    {
        $attribute_name = 'image';
        $disk = 'authors_images';
        $destination_path = '';

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
    
    public static function boot()
    {
        parent::boot();
        static::deleting(function(Authors $author) {
            if (isset($author->image))
            {
				\Storage::disk('authors_images')->detach($author->image);
			}
			//does not work
			$author->posts()->delete();
        });
    }
}
