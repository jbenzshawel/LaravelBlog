<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 2/29/16
 * Time: 9:38 PM
 */

namespace App\Repositories;

use Hash;
use App\Repositories\Eloquent\Repository;

class UserRepository extends Repository
{

    /**
     * @return User Model
     */
    function model()
    {
        return 'App\User';
    }


    public function ChangeName($name, $id)
    {
        if(isset($name) && $id > 0) {
            $this->Update([
                "name" => $name
            ], $id);
            return true;
        }
        return false;
    }

    public function ChangeEmail($email, $id)
    {
        if(isset($email) && $id > 0) {
            $this->Update([
                "email" => $email
            ], $id);
            return true;
        }
        return false;
    }

    public function ChangePassword($password, $id)
    {
        if(strlen($password) == 60 && $id > 0) {
            DB::table('users')->where('id', $id)->update([
                "password" => Hash::make($password)
            ], $id);
            return true;
        }
        return false;
    }
}