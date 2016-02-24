<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $id;

    public static $name;

    public static $email;

    public function __construct()
    {

    }

    public static function changeName($name, $id)
    {
        if(isset($name) && $id > 0) {
            DB::table('users')->where('id', $id)->update([
               "name" => $name
            ]);
            return true;
        }
        return false;
    }
}
