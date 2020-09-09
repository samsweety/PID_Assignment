<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    public $timestamps = false;

    protected $fillable=[
        'gName','price'
    ];
}
