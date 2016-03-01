<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 2/28/16
 * Time: 2:27 PM
 */

namespace App\Repositories;

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
        return $this->Update([ 'Visible' => false, 'updated_at' => date('Y-m-d H:i:s') ], $id);
    }

    public function ShowPost($id)
    {
        return $this->Update([ 'Visible' => true, 'updated_at'=> date('Y-m-d H:i:s') ], $id);
    }

    public function Excerpts()
    {
        $contentList =  $this->Fields(['content', 'id']);
        $excerptList = array();
        $excerptIds = array();
        foreach($contentList as $contentObj) {
            $strippedContent = strip_tags($contentObj->content);
            if(strlen($strippedContent) > 74) {
                $offset = 75;
            } else {
                $offset= 0;
            }
            // create excerpt that is the content ending after the first period passed 75 characters
            $excerpt = substr($strippedContent, 0, strpos($strippedContent, ".", $offset) + 1);
            $excerptIds[$contentObj->id] = $excerpt;
            array_push($excerptList, ["id" => $contentObj->id, "excerpt" => $excerpt]);
        }
        $excerpt = ["ById" => $excerptIds, "List" => $excerptList];
        return $excerpt;
    }
}