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
        return $this->Update([ 'Approved' => true ], $id, "ID");
    }

    /**
     * @param $id
     * @return mixed
     */
    public function UnApprove($id)
    {
        return $this->Update([ 'Approved' => false ], $id, "ID");
    }

    /**
     * @param $postId
     * @return array
     */
    public function GetCommentsByPostId($postId)
    {
        $commentsList = array();
        $comments =  $this->_model->where('PostID', $postId)->get();
        foreach($comments as $comment) {
            if (isset($comment->ParentID) && $comment->Approved) {
               array_push($commentsList[$comment->ParentID]["reply"], $comment);
            } else {
                $commentsList[$comment->id] = array("content" => $comment, "reply" => array());
            }
        }
        return $commentsList;
    }
}