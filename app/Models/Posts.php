<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts_data';
    public $timestamps = false;
    protected $fillable = ['title', 'description'];
    protected $hidden = ['author_id'];

    public function author()
    {
        return $this->belongsTo('App\Models\Posts\Authors');
    }

    public function index_image()
    {
        return $this->belongsTo('App\Models\Posts\Images', 'index_image');
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
