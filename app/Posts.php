<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    //
    protected $table = "posts"; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content'
    ];

    public static $id;

    public static $title;

    public static $content;

    public static $userID;

    public static $posts;

    public static $datetime;

    public function __construct($title = "", $content = "", $id = "", $userID = "")
    {
        self::$posts = DB::table('posts');
        self::$id = $id;
        self::$title = $title;
        self::$content = $content;
        self::$userID = $userID;
    }

    public static function SavePost()
    {
        $result = false;

        if(isset(self::$title) && isset(self::$content)) {
            // create post
            if(empty(self::$id)) {
                self::$posts->insert([
                    [ "title" => self::$title, "content" => self::$content, "UserID" => self::$userID, "Visible" => true ]

                ]);
                $result = true;
            } else { // update post
                self::$posts->where('id', self::$id)->update([
                     "title" => self::$title, "content" => self::$content, "Visible" => true
                ]);
                $result = true;
            }
        }

        return $result;
    }

    public static function DeletePost($id = null) {
        $result = false;
        if(isset($id)) {
            DB::table('posts')->where('id', $id)->delete();
            $result = true;
        } elseif(isset(self::$id)) {
            DB::table('posts')->where('id', self::$id)->delete();
            $result = true;
        }
        return $result;
    }

    public static function HidePost($id = null) {
        if(isset($id)) {
            date_default_timezone_set('America/Chicago');
            DB::table('posts')->where('id', $id)->update([
                "visible" => false, "updated_at" => date("Y-m-d H:i:s")
           ]);
            return true;
        }
        return false;
    }

    public static function ShowPost($id = null) {
        if(isset($id)) {
            date_default_timezone_set('America/Chicago');
            DB::table('posts')->where('id', $id)->update([
                "visible" => true, "updated_at" => date("Y-m-d H:i:s")
            ]);
            return true;
        }
        return false;
    }

    public static function ListPosts()
    {
        $titleList = DB::table('posts')->lists('id', 'title');
        $contentList = DB::table('posts')->lists('content', 'id');
        $dateList = DB::table('posts')->lists('created_at', 'id');
        $updatedList = DB::table('posts')->lists('updated_at', 'id');
        $visibleList = DB::table('posts')->lists('visible', 'id');
        $postArray = array();
        foreach($titleList as $title => $id) {
            $datetime = new \DateTime();
            $lastUpdated = new \DateTime($updatedList[$id]);
            $dateDiff = $datetime->diff($lastUpdated)->format('%R%a days');
            $dateDiff = $dateDiff[0] == "-" ? "" : $dateDiff;
            if(strlen($contentList[$id]) > 74) {
                $taglineOffset = 75;
            } else {
                $taglineOffset= 0;
            }
            array_push($postArray, [
                "id" => $id,
                "title" => $title,
                "content" => $contentList[$id],
                "excerpt" => substr($contentList[$id], 0, strpos($contentList[$id], ".", $taglineOffset) + 1),
                "dateCreated" => $dateList[$id],
                "lastUpdated" => $dateDiff,
                'visible' => $visibleList[$id]
            ]);

        }
        return $postArray;
    }

    public static function GetById($id) {
        $post = DB::table('posts')->where("id", $id)->first();
        if(isset(self::$id)) {
          $Posts = new Posts($post->title, $post->content, $post->id, $post->userID);
          $post = $Posts;
        }
        return $post;
    }
}
