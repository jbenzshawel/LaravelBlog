<?php

namespace App;

use DB;
use Faker\Provider\zh_TW\DateTime;
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

    public static $PostId;

    public static $Comments;

    public static $CommentList;

    public static $Comment;

    public static $Name;

    public static $Email;

    public static $HasParent;

    public static $ParentId;

    public  function __construct($PostId = null, $id = null, $Comment = null, $ParentId = null, $name = null, $email = null)
    {
        self::$id = $id;
        self::$PostId = $PostId;
        self::$Comments = DB::table('comments');
        self::$Comment = $Comment;
        self::$HasParent = isset($ParentId) ? true : false;
        self::$ParentId = $ParentId;
        self::$Name = $name;
        self::$Email = $email;
    }

    public static function GetAllComments()
    {
        self::$CommentList = DB::table('comments')->get();
        return self::$CommentList;
    }

    public static function GetCommentsByPostId($postId)
    {
        if(!isset($postId)) $postId = selef::$PostId;
        if(DB::table('comments')->where('PostID', $postId)->count() > 0) {
            self::$CommentList = DB::table('comments')->where('PostID', $postId)->get();
        } else {
            self::$CommentList = null;
        }
        return self::$CommentList;
    }

    public static function ApproveComment($commentId)
    {
        if(!isset($commentId)) $commentId = self::$id;
        if( DB::table('comments')->where('ID', $commentId)->count() > 0) {
            DB::table('comments')->where('ID', $commentId)->update([
                "Approved" => true
            ]);
            return true;
        }
        return false;
    }

    public static function DeleteComment($commentId) {
        if(!isset($commentId)) $commentId = self::$id;
        if (DB::table('comments')->where('ID', $commentId)->count() > 0) {
            DB::table('comments')->where('ID', $commentId)->delete();
        }

    }
    public static function SaveComment()
    {

        $date = date("Y-m-d H:i:s");
        $comment = [
            "PostID" => self::$PostId,
            "Comment" => self::$Comment,
            "ParentID" => self::$ParentId,
            "Approved" => false,
            "Name" => self::$Name,
            "Email" => self::$Email,
            "DateCreated" => $date
        ];
        if(empty(self::$id))
        {
            self::$Comments->insert([ $comment ]);
            return true;
        } else {
            self::$Comments->where("ID", self::$id)->update([ $comment ]);
            return true;
        }
        return false;
    }
}
