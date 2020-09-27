<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Labels extends Model
{
    use CrudTrait;
    protected $table = 'posts_labels';
    public $timestamps = false;
    protected $fillable = ['name', 'color'];
    protected $hidden = ['pivot'];

    public function posts()
    {
        return $this->belongsToMany('App\Models\Posts', 'posts_pivot_labels_data');
    }

    protected static function boot()
    {
        parent::boot();

        // @codeCoverageIgnoreStart
        static::deleting(function (Labels $label) {
            $label->posts()->detach();
        });
        // @codeCoverageIgnoreEnd
    }
}
