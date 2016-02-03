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

    public  static $userID;

    public static $ListPosts;

    public static $posts;

    public  static function SavePost() {
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

    public function __construct($title = "", $content = "", $id = "", $userID = "")
    {
        self::$ListPosts = DB::table('posts')->get();;
        self::$posts = DB::table('posts');
        self::$id = $id;
        self::$title = $title;
        self::$content = $content;
        self::$userID = $userID;
    }
}
