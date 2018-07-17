<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'posts_images';
    public $timestamps = false;
    protected $fillable = ['url'];
    protected $hidden = ['post_id'];

    public function post()
    {
        return $this->belongsTo('App\Models\Posts', 'post_id');
    }

    public function author()
    {
        return $this->hasOne('App\Models\Posts\Authors');
    }
}
