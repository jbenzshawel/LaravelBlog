<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

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
        self::$id = 1;//Auth::user()->id;
        //self::$name = Auth::user()->name;
        //self::$email = Auth::user()->email;
    }

    public static function changeName($name)
    {
        if(isset($name) && self::$id > 0) {
            DB::table('users')->where('id', self::$id)->update([
               "name" => $name
            ]);
            return true;
        }
        return false;
    }
}
