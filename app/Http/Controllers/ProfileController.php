<?php

namespace App\Http\Controllers;

use App\User;
use App\Department;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
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
        $user = User::with(['department', 'category'])->where('id', auth()->id())->get();

        return $user;
    }
    public function department()
    {
        return Department::get();
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
        $user = User::findorfail(auth()->id());

        $data = request()->validate([
            'image' => 'sometimes|file|image|max:5000',
        ]); 

        $user->update($data);
               
        if(request()->has('image')){
            auth()->user()->update([
                'image' => request()->image->store('uploads', 'public'),
            ]);

            $image = Image::make(('storage/'. auth()->user()->image))->fit(300, 300);

            /***
             * FOR PRODUCTION SERVER
             *  $img = Image::make($request->file('image')->getRealPath())->fit(300, 300);
             *
             *  $img->save('storage/'.auth()->user()->image);
             */

            $image->save();
        }

        $msg ='Profile Image uploaded successfully.';


        return['message' => $msg];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if($request->has('firstname'))
        {
            $data = request()->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'department_id' => 'required',
                'email' => 'required'
            ]);
            $msg = 'User Updated.';
        }
        elseif($request->has('password'))
        {
            $data = request()->validate([
                'current_password' => 'required',
                'password' => 'required|confirmed|min:6|different:current_password',
                'password_confirmation' => 'required',
            ]);

    
            if(Hash::check($request->get('current_password'), $user->password)){
                $data = ['password' => Hash::make($request->get('password'))];

                $msg = 'Password successfully changed.';
                
            }
            else{
                $msg = 'Invalid current password entered!';
            }
            
        }else{
            dd($request->image);



        }       

        $user->update($data);

        return['message' => $msg];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    private function storeImage($data)
    {
        if(request()->has('image')){
            auth()->user()->update([
                'image' => $data['image']->store('uploads', 'public'),
            ]);

            $image = Image::make(('storage/'. auth()->user()->image));

            $image->save();
        }
    } 
}
