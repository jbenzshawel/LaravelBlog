<?php

namespace App\Http\Controllers;

use Auth;
use App\Posts;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PostsController extends  BaseController
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
        $viewData["Posts"] = new Posts();
        $viewData["TitleList"] = array();
        $Posts = new Posts(); 
        $Posts::chunk(100, function($posts, $viewData) {
        	foreach($posts as $post) {
        		array_push($viewData["TitleList"], $post->title);
        	}
        });
        return view('posts', $viewData);
    }

    // GET: /posts/create
    public function create() 
    {
    	$viewData = array(); 
        $viewData["user"] = Auth::user(); 

    	return view('posts/create', $viewData);
    }

    // POST: /posts/createPostback
    public function createPostback($title, $content, $userID)
    {
        $status = false;
    	if(isset($title) && isset($content) && isset($userID)) {
            $Posts = new Posts($title, $content, "", $userID);
            $status = $Posts::SavePost();
        }

        return $status;
    }
}