<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserActivation;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->id() == 1)
        {
            $users = User::with(['department', 'category'])->where('id', '>', '1')->paginate(10);
            return $users;
        }
        return back();
    }

    public function Category()
    {
        $categories = Category::get();
        
        return $categories;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if(auth()->id() == 1)
        {
            if($request->has('password'))
            {
                $data = ['password' => Hash::make($request->get('password'))];

                $user->update($data);

                return['message' => 'User password reseted!'];
            }else
            {
                $data = request()->validate([
                    'category_id' => 'sometimes',
                    'permission' => 'sometimes',
                ]);
           
                $user->category_id = request()->category_id;
                $user->permission = request()->permission ? True : False;
                
                $user->update();
                // send an email to the user

                $admin = auth()->user()->email;
               
                $when = now()->addMinutes(10);
                Mail::to($user->email)->later($when, new UserActivation($user, $admin));
    

               return['message' => 'User Activated!'];
            }

        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(auth()->id() == 1)
        {
            $user->delete();

            return['message' => 'User Deleted!'];
        }
        return back();
    }
}
