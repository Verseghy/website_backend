<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'posts_images';
    public $timestamps = false;
    protected $fillable = ['url'];

    public function post() {
        return $this->belongsTo('App\Models\Posts');
    }

    public function author() {
        return $this->hasOne('App\Models\Posts\Authors');
    }
}
