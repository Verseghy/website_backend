<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Posts extends Model
{
    use crudTrait;
    protected $table = 'posts_data';
    
    protected $fillable = ['title', 'description', 'color', 'index_image', 'images', 'content'];
    protected $hidden = ['author_id','created_at','updated_at'];

    protected $casts = [
        'images' => 'array'
    ];


    public function author()
    {
        return $this->belongsTo('App\Models\Posts\Authors');
    }

    public function labels()
    {
        return $this->belongsToMany('App\Models\Posts\Labels', 'posts_pivot_labels_data');
    }
    
    /**
     * @codeCoverageIgnore
     */
    public function setIndexImageAttribute($value)
    {
        $attribute_name = 'index_image';
        $disk = 'posts_images';
        $destination_path = 'index';

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
    
    /**
     * @codeCoverageIgnore
     */
    public function setImagesAttribute($value)
    {
        $attribute_name = 'images';
        $disk = 'posts_images';
        $destination_path = "";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
    
    
    public static function boot()
    {
        parent::boot();
        
        // @codeCoverageIgnoreStart
        static::deleting(function (Posts $post) {
            if (count((array)$post->images)) {
                foreach ($post->images as $file_path) {
                    \Storage::disk('posts_images')->delete($file_path);
                }
            }
            if (isset($post->index_image)) {
                \Storage::disk('posts_images')->delete($post->index_image);
            }
            
            $post->author()->dissociate();
            $post->labels()->detach();
        });
        // @codeCoverageIgnoreEnd
    }
}
