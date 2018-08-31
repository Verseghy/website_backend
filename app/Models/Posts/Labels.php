<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Labels extends Model
{
    use CrudTrait;
    protected $table = 'posts_labels';
    public $timestamps = false;
    protected $fillable = ['name', 'color'];
    protected $hidden = ['pivot'];

    public function posts()
    {
        $this->belongsToMany('App\Models\Posts', 'posts_pivot_labels_data');
    }
}
