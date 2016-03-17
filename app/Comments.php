<?php

namespace App;

use DB;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	//
    protected $table = "comments"; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name', 'Email', 'Comment', 'PostID', 'ParentID', 'Approved'
    ];


}
