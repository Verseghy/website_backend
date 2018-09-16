<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\Posts;

class Authors extends Model
{
    use CrudTrait;
    protected $table = 'posts_authors';
    public $timestamps = false;
    protected $fillable = ['name', 'description', 'image'];
    protected $hidden = [];

	// should be tested via getPostsByAuthor
    public function posts()
    {
        return $this->hasMany('App\Models\Posts', 'id', 'author_id');
    }
    
	/**
	 * @codeCoverageIgnore
	 */
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
        
        // codeCoverageIgnoreStart
        static::deleting(function (Authors $author) {
            if (isset($author->image)) {
                \Storage::disk('authors_images')->delete($author->image);
            }

            Posts::where('author_id', '=', $author->id)->get()->each(function (Posts $p) {
                $p->author()->dissociate();
                $p->save();
            });
        });
        // codeCoverageIgnoreEnd
    }
}
