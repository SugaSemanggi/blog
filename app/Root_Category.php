<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Root_Category extends Model
{
    protected $fillable = [
        'name','description'
    ];
    public function category()
    {
        return $this->hasMany('App\Category');
    }
}
