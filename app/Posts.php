<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    //
    protected $table = "posts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content'
    ];


}