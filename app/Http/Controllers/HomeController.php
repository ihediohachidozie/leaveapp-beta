<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Leave;
use App\User;
use DB;
use App\Department;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $comments = Comment::where(['user_id' => auth()->id(), 'status' => '0'])->orderBy('id', 'DESC')->Take(1)->get();
        return view('home', compact('comments'));
    }
    public function users()
    {
        return User::all()->count();
    }

    public function departments()
    {
        return Department::all()->count();
    }

    public function pendingRequests()
    {
        return Leave::where(['user_id' => auth()->id(), 'status' => 1])->count();
    }

    public function approvalRequests()
    {
        return Leave::where(['approval_id' => auth()->id(), 'status' => 1])->count();

    }


}
