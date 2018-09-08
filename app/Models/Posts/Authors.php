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
        return $this->hasMany('App\Models\Posts');
    }
    
    
    // @codeCoverageIgnoreStart
    
    // Accessor to get full URL of image
    public function getImageAttribute()
    {
        $disk = 'images';
        $filename = $this->attributes['image'];
        
        return $filename ? self::public_url($disk, $filename) : null;
    }
    
    
    // Mutator to upload an image
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "images";
        $destination_path = "images";

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
            $this->attributes[$attribute_name] = /*$destination_path.'/'.*/$filename;
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
        $this->append('image');
        return parent::toJson($options);
    }
    
    // 'Custom' serializer to use accessor for image attribute
    public function toArray($options=0)
    {
        $this->append('image');
        return parent::toArray($options);
    }
    
    // @codeCoverageIgnoreEnd
}
