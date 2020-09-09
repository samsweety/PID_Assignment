<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $timestamps = false;
    
    protected $fillable=[
        'id','deliver','orderTime'
    ];

}
