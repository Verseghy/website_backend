<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Posts extends Model
{
    use crudTrait;
    protected $table = 'posts_data';
    
    protected $fillable = ['title', 'description', 'color', 'index_image'];
    protected $hidden = ['author_id','created_at','updated_at'];

    public function author()
    {
        return $this->belongsTo('App\Models\Posts\Authors');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Posts\Images', 'post_id');
    }

    public function labels()
    {
        return $this->belongsToMany('App\Models\Posts\Labels', 'posts_pivot_labels_data');
    }
    
    
    // Accessor to get full URL of image
    public function getIndexImageAttribute()
    {
        $disk = 'images';
        $filename = $this->attributes['index_image'];
        return $filename ? self::public_url($disk, $filename) : null;
    }
    
    // Mutator to upload an image
    public function setIndexImageAttribute($value)
    {
        $attribute_name = "index_image";
        $disk = "images";
        $destination_path = "images/postImages/indexes";
        $path_prefix = 'postImages/indexes';

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($value);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = $path_prefix.'/'.$filename;
        }
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
        $this->append('url');
        return parent::toJson($options);
    }
    
    // 'Custom' serializer to use accessor for image attribute
    public function toArray($options=0)
    {
        $this->append('url');
        return parent::toArray($options);
    }
}
