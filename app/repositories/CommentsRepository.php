<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 2/28/16
 * Time: 2:39 PM
 */

namespace App\Repositories;

use App\Repositories\Interfaces\IRepository;
use App\Repositories\Eloquent\Repository;

class CommentsRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Comments';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function Approve($id)
    {
        return $this->Update([ 'Approved' => true], $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function UnApprove($id)
    {
        return $this->Update([ 'Approved' => false], $id);
    }

    public function GetCommentsByPostId($postId)
    {
        return $this->_model->where('PostID', $postId)->get();
    }
}