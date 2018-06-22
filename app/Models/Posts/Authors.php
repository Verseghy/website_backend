<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Authors extends Model
{
    protected $table = 'posts_authors';
    public $timestamps = false;
    protected $fillable = ['name', 'description'];

    public function posts() {
        return $this->hasMany('App\Models\Posts');
    }

    public function image() {
        return $this->belongsTo('App\Models\Posts\Images');
    }
}
