<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\User;
use App\Posts;
use App\Comments;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
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
    public function index()
    {
        $viewData = array(); 
        $viewData["user"] = Auth::user(); 
        date_default_timezone_set('America/Chicago');
        $viewData["lastUpdated"] = date('F d, Y, g:i a', strtotime(Auth::user()->updated_at));
        $viewData["CommentList"] = Comments::GetAllComments();
        $viewData["PostsList"] = Posts::ListPosts();
        return view('home', $viewData);
    }

    /**
     * Postback for changing a user's name
     *
     * @param Request $request
     * @return string
     */
    public function changeNamePostback(Request $request)
    {
        $status = "false";
        $user = $request->all();
        if (isset($user["name"]) && strlen($user["name"]) > 2) {
            User::changeName($user["name"], $request->user()->id);
            $status = "true";
        }
        return $status;
    }

    /**
     * Postback for changing a user's email
     *
     * @param Request $request
     * @return string
     */
    public function changeEmailPostback(Request $request)
    {
        $status = "false";
        $user = $request->all();
        if (isset($user["email"]) && filter_var($user["email"], FILTER_VALIDATE_EMAIL)) {
            User::changeEmail($user["email"], $request->user()->id);
            $status = "true";
        }
        return $status;
    }

    /**
     * Postback for changing a user's password
     *
     * @param Request $request
     * @return string
     */
    public function changePasswordPostback(Request $request) {
        $status = "false";
        $user = $request->all();
        if(Auth::attempt(['email' => $request->user()->email, 'password' => $user["oldPassword"]])) {
            User::changePassword(Hash::make($user["newPassword"]), $request->user()->id);
            $status = "true";
        }
        return $status;
    }
}