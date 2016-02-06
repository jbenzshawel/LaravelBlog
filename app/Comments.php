<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	//
    protected $table = "comments"; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user', 'email', 'comment'
    ];

    public static $id;

    public static $Comments;

    public static $Comment;

    public static $CommentList;

    public static $HasParent;

    public  static $ParentId;

    public  function __construct($id = null, $Comment = null, $ParentId = null)
    {
        self::$id = $id;
        self::$Comments = DB::table('comments');
        self::$Comment = $Comment;
        self::HasParent = isset($ParentId) ? true : false;
        self::$ParentId = $ParentId;
    }

    public static function GetCommentsByPostId($postId)
    {
        self::$CommentList = DB::table('comments')->where('PostID', $postId)->get()
        return self::$CommentList;
    }

    public  static function SaveComment()
    {
        if(empty(self::$id))
        {
            self::$Comments->insert([
               [ "Comment" => self::$Comment, "HasParent" => self::$HasParent, "ParentID" => self::$ParentId ]
            ]);
        }
        else
        {
            self::$Comments->insert([
                [ "Comment" => self::$Comment, "HasParent" => self::$HasParent, "ParentID" => self::$ParentId ]
            ]);
        }
    }


    public function getApprovedAttribute($approved)
    {
        return (intval($approved) == 1) ? 'yes' : 'no';
    }

    public function setApprovedAttribute($approved)
    {
        $this->attributes['approved'] = ($approved === 'yes') ? 1 : 0;
    }
}
