<?php

namespace App\Http\Controllers;

use Auth;
use App\Posts;
use App\Comments;
use App\Http\Requests;
use App\Repositories\PostsRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PostsController extends BaseController
{
    /**
     * @var Posts
     */
    private $_repository;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->_repository = new PostsRepository();
    }

    /**
     * Show posts list
     *
     * @return \Illuminate\Http\Response
     */
    // GET: /posts/
    public function index()
    {
        $viewData = array(); 
        $viewData["user"] = Auth::user(); 
        $viewData["lastUpdated"] = date('F d, Y, g:i a', strtotime(Auth::user()->updated_at));

        $viewData["PostList"] = $this->_repository->All();

        return view('posts', $viewData);
    }

    /**
     * Show posts by id
     *
     * @param int post id
     * @return \Illuminate\Http\Response
     */
    // GET: /posts/{id}
    public function getPost($id)
    {
        $viewData = array();
        if(isset($id)) {
            $viewData["post"] = Posts::GetById($id);
            $viewData["CommentsList"] = Comments::GetCommentsByPostId($id);
        }

        return view('post', $viewData);
    }

    /**
     * Get posts by id to edit
     *
     * @param int post id
     * @return \Illuminate\Http\Response
     */
    // GET: /posts/{id}/edit
    public function editPost($id)
    {
        $viewData = array();
        if(isset($id)) {
            $viewData["post"] = Posts::GetById($id);
            $viewData["CommentsList"] = Comments::GetCommentsByPostId($id);
        }

        return view('posts/edit', $viewData);
    }

    /**
     * Show create post page
     *
     * @return \Illuminate\Http\Response
     */
    // GET: /posts/create
    public function create() 
    {
    	$viewData = array(); 
        $viewData["user"] = Auth::user(); 

    	return view('posts/create', $viewData);
    }

    /**
     * Postback for ajax to save a post
     *
     * @param \Illuminate\Http\Request content and userID variables
     * @return string response
     */
    // POST: /posts/create
    public function createPostback(Request $request)
    {
        $status = "false";
        $post = $request->all();
        if(!isset($post["userID"])) $post["userID"] = $request->user()->id;
        if (isset($post["title"]) && isset($post["content"]) && isset($post["userID"])) {
            if (isset($post["id"])) {
                $Posts = new Posts($post["title"], $post["content"], $post["id"], $post["userID"]);
            } else {
                $Posts = new Posts($post["title"], $post["content"], "", $post["userID"]);
            }
            if ($Posts::SavePost()) {
                $status = "true";
            }
        }

        return $status;
    }

    /**
     * Postback for ajax to delete a post
     *
     * @param Request $request postID variable
     * @return string
     */
    // POST: /posts/delete
    public function deletePostback(Request $request)
    {
        if($this->updatePost($request, 'DeletePost'))
            return "true";
        return "false";
    }

    /**
     * Postback for ajax to hide a post
     *
     * @param Request $request postID variable
     * @return string
     */
    // POST: /posts/hide
    public function hidePostback(Request $request)
    {
        if($this->updatePost($request, 'HidePost'))
            return "true";
        return "false";
    }

    /**
     * Postback for ajax to show a post
     *
     * @param Request $request postID variable
     * @return string
     */
    // POST: /posts/show
    public function showPostback(Request $request)
    {
        if($this->updatePost($request, 'ShowPost'))
            return "true";
        return "false";
    }


    /**
     * Postback for ajax to save a comment
     *
     * @param \Illuminate\Http\Request
     * @return string response
     */
    // POST: /posts/createComment
    public function createCommentPostback(Request $request)
    {
        $status = "false";
        $comment = $request->all();
        if (isset($comment["PostId"]) && isset($comment["Comment"]) && isset($comment["HasParent"]) && isset($comment["Name"])) {
            $email = isset($comment["Email"]) ? $comment["Email"] : "";
            if(!isset($comment["CommentId"])) {
                if (!$comment["HasParent"]) {
                    $Comments = new Comments($comment["PostId"], null, $comment["Comment"], null, $comment["Name"], $email);
                } else {
                    $Comments = new Comments($comment["PostId"], null, $comment["Comment"], $comment["ParentId"], $comment["Name"], $email);
                }
            } else {
                if (!$comment["HasParent"]) {
                    $Comments = new Comments($comment["PostId"], $comment["id"], $comment["Comment"], null, $comment["Name"], $email);
                } else {
                    $Comments = new Comments($comment["PostId"], $comment["id"], $comment["Comment"], $comment["ParentId"], $comment["Name"], $email);
                }
            }

            if($Comments::SaveComment()) {
                $status = "true";
            }
        }

        return $status;
    }

    /**
     * Postback for approving a comment
     *
     * @param Request $request
     * @return string true or false of action (laravel requires response to be string)
     */
    // POST: /posts/approveComment
    public function approveCommentPostback(Request $request)
    {
        if($this->updateComment($request, "ApproveComment"))
            return "true";
        return "false";
    }

    /**
     * Postback for un-approving a comment
     *
     * @param Request $request
     * @return string true or false of action (laravel requires response to be string)
     */
    // POST: /posts/unapproveComment
    public function unapproveCommentPostback(Request $request)
    {
        if($this->updateComment($request, "UnApproveComment"))
            return "true";
        return "false";
    }

    /**
     * Postback for deleting a comment
     *
     * @param Request $request
     * @return string true or false of action (laravel requires response to be string)
     */
    // POST: /posts/deleteComment
    public function deleteCommentPostback(Request $request)
    {
        if($this->updateComment($request, "DeleteComment"))
            return "true";
        return "false";
    }

    /**
     * Private function for updating comments.
     *
     * @param Request $request
     * @param $callbackAction is the string name of the static function in the Comments object
     * @return bool response of callback function
     */
    // POST: /posts/updateComment
    private function updateComment(Request $request, $callbackAction) {
        $class = 'App\Comments';
        $comment = $request->all();
        if (isset($comment["commentId"])) {
           $status = call_user_func_array(array($class, $callbackAction), array($comment["commentId"]));
           if($status)
               return true;
        }
        return false;
    }

    /**
     * Private function for updating posts.
     *
     * @param Request $request
     * @param $callbackAction is the string name of the static function in the Posts object
     * @return bool response of callback function
     */
    // POST: /posts/updatePost
    private function updatePost(Request $request, $callbackAction) {
        $class= 'App\Posts';
        $post = $request->all();
        if (isset($post["postId"]) && gettype($post["postId"]) == "integer") {
            $status = call_user_func_array(array($class, $callbackAction), array($post["postId"]));
            if($status)
                return true;
        }
        return false;
    }
}