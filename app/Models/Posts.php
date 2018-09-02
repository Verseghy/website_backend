<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
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
}
