<?php

namespace App;

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
        'user', 'email', 'comment'
    ];

     public function post()
    {
        return $this->belongsTo('Posts');
    }

    public function getApprovedAttribute($approved)
    {
        return (intval($approved) == 1) ? 'yes' : 'no';
    }

    public function setApprovedAttribute($approved)
    {
        $this->attributes['approved'] = ($approved === 'yes') ? 1 : 0;
    }
}
