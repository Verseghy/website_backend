<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Posts extends Model
{
    use crudTrait;
    protected $table = 'posts_data';
    
    protected $fillable = ['title', 'description', 'color', 'index_image', 'images'];
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
    
    
    public function setIndexImageAttribute($value)
    {
        $attribute_name = 'index_image';
        $disk = 'posts_images';
        $destination_path = 'index';

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }
    
    public function setImagesAttribute($value)
	{
		$attribute_name = 'images';
		$disk = 'posts_images';
		$destination_path = "";

		$this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
	}
    
    // Get a public URL from a path
    // Stolen from https://stackoverflow.com/questions/37017880/laravel-filesystem-storage-local-public-file-urls-not-working#comment61589587_37018544
    protected function public_url($disk, $path = '')
    {
        $fs = \Storage::disk($disk);

        if ($fs->getAdapter() instanceof \League\Flysystem\Adapter\Local) {
            return asset($fs->url($path));
        }

        return $fs->url($path);
    }
    
    
    // 'Custom' serializer to use accessor for image attribute
    public function toJson($options=0)
    {
        $this->append('index_image');
        return parent::toJson($options);
    }
    
    // 'Custom' serializer to use accessor for image attribute
    public function toArray($options=0)
    {
        $this->append('index_image');
        return parent::toArray($options);
    }
    // @codeCoverageIgnoreEnd
    
    
	public static function boot()
    {
        parent::boot();
        static::deleting(function(Posts $post) {
            if (count((array)$post->images)) {
                foreach ($post->images as $file_path) {
                    \Storage::disk('posts_images')->delete($file_path);
                }
            }
            if (isset($post->index_image))
            {
				\Storage::disk('posts_images')->delete($post->index_image);
			}
        });
    }
}
