<?php

namespace App\Http\Controllers;

use Auth;
use App\Posts;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PostsController extends BaseController
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    // GET: /posts/
    public function index()
    {
        $viewData = array(); 
        $viewData["user"] = Auth::user(); 
        date_default_timezone_set('America/Chicago');
        $viewData["lastUpdated"] = date('F d, Y, g:i a', strtotime(Auth::user()->updated_at));
        $viewData["PostList"] = Posts::ListPosts();

        return view('posts', $viewData);
    }

    // GET: /posts/create
    public function create() 
    {
    	$viewData = array(); 
        $viewData["user"] = Auth::user(); 

    	return view('posts/create', $viewData);
    }

    // /posts/{id}
    public function getPost($id)
    {
        $viewData = array();
        if(isset($id)) {
            $viewData["post"] = Posts::GetById($id);
        }
        return view('post', $viewData);
    }

    // POST: /posts/createPostback
    public function createPostback(Request $request)
    {
        $status = "false";
        $post = $request->all();

    	if(isset($post["title"]) && isset($post["content"]) && isset($post["userID"])) {
            $Posts = new Posts($post["title"], $post["content"], "", $post["userID"]);
            if($Posts::SavePost()) {
                $status = "true";
            }
        }

        return $status;
    }
}