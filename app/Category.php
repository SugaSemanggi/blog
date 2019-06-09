<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name','description','root_category_id'
    ];

    public function root_category()
    {
        return $this->belongsTo('App\Root_Category');
    }

    public function post()
    {
        return $this->hasMany('App\Post');
    }
}
