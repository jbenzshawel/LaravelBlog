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
        return 'App\Models\Comments';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function Approve($id)
    {
        return $this->modal->where('id', $id)->update([ 'Approved' => true]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function UnApprove($id)
    {
        return $this->modal->where('id', $id)->update([ 'Approved' => fale]);
    }

    public function GetCommentsByPostId($postId)
    {
        return $this->modal->where('PostID', $postId)->get();
    }
}