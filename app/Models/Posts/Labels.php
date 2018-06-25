<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Labels extends Model
{
    protected $table = 'posts_labels';
    public $timestamps = false;
    protected $fillable = ['name', 'color'];

    public function posts() {
        $this->belongsToMany('App\Models\Posts', 'posts_pivot_labels_posts');
    }
}
