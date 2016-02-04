<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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

    public static function SavePost()
    {
        $result = false;

        if(isset(self::$title) && isset(self::$content)) {
            // create post
            if(empty(self::$id)) {
                self::$posts->insert([
                    [ "title" => self::$title, "content" => self::$content, "UserID" => self::$userID ]

                ]);
                $result = true;
            } else { // update post
                self::$posts->where('id', self::$id)->update([
                    [ "title" => self::$title, "content" => self::$content ]
                ]);
                $result = true;
            }
        }

        return $result;
    }

    public static function ListPosts()
    {
        $titleList = DB::table('posts')->lists('id', 'title');
        $contentList = DB::table('posts')->lists('content', 'id');
        $postArray = array();
        foreach($titleList as $title => $id) {
            if(strlen($contentList[$id]) > 74) {
                $taglineOffset = 75;
            } else {
                $taglineOffset= 0;
            }
            array_push($postArray, [
                "id" => $id,
                "title" => $title,
                "content" => $contentList[$id],
                "tagline" => substr($contentList[$id], 0, strpos($contentList[$id], ".", $taglineOffset) + 1)
            ]);

        }
        return $postArray;
    }

    public  static function GetById($id) {
        $post = DB::table('posts')->where("id", $id)->first();
        return $post;
    }

    public function __construct($title = "", $content = "", $id = "", $userID = "")
    {
        self::$posts = DB::table('posts');
        self::$id = $id;
        self::$title = $title;
        self::$content = $content;
        self::$userID = $userID;
    }
}
