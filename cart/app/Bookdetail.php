<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookdetail extends Model
{
    public $timestamps = false;

    protected $fillable=[
        'bid','gid','amount'
    ];
}
