<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 2/28/16
 * Time: 2:27 PM
 */

namespace App\Repositories;

//use App\Repositories\Interfaces\IRepository;
use App\Repositories\Eloquent\Repository;

class PostsRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Posts';
    }

    public function HidePost($id)
    {
        return $this->modal->where('id', $id)->update([ 'Visible' => false, 'updated_at', date('Y-m-d H:i:s') ]);
    }

    public function ShowPost($id)
    {
        return $this->modal->where('id', $id)->update([ 'Visible' => true, 'updated_at', date('Y-m-d H:i:s') ]);
    }
}