<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 3/6/16
 * Time: 4:38 PM
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\PostsRepository;
use App\Repositories\CommentsRepository;
use App\Repositories\UserRepository;
use Illuminate\Routing\Controller as BaseController;

class PublicController extends BaseController
{
    private $_PostsRepository;

    private $_CommentsRepository;

    private $_UserRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_PostsRepository = new PostsRepository();
        $this->_CommentsRepository = new CommentsRepository();
        $this->_UserRepository = new UserRepository();
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
        $viewData["PostList"] = $this->_PostsRepository->Paginate($this->_UserRepository->Find(1, ['pagination'])->pagination);
        $viewData["PostExcerpts"] = $this->_PostsRepository->Excerpts();

        return view('posts', $viewData);
    }

    /**
     * Show posts list
     *
     * @return \Illuminate\Http\Response
     */
    // GET: /posts/
    public function feed()
    {
        $viewData = array();
        $viewData["posts"] = $this->_PostsRepository->All();
        $viewData["PostExcerpts"] = $this->_PostsRepository->Excerpts();

        return view('feed', $viewData);
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
        if (isset($id)) {
            $viewData["post"] = $this->_PostsRepository->Find($id);
            $viewData["CommentsList"] = $this->_CommentsRepository->GetCommentsByPostId($id);
        }

        return view('post', $viewData);
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
            if (!$comment["HasParent"]) {
                $this->_CommentsRepository->Create([
                    "Name" => $comment["Name"], "Email" => $email, "Comment" => $comment["Comment"], "PostID" =>$comment["PostId"]
                ]);
            } else {
                $this->_CommentsRepository->Create([
                    "Name" => $comment["Name"], "Email" => $email, "Comment" => $comment["Comment"], "PostID" =>$comment["PostId"], "ParentID" => $comment["ParentID"]
                ]);
            }
            $status = "true";

        }

        return $status;
    }
}