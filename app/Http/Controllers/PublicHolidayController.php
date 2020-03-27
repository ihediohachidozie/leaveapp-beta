<?php

namespace App\Http\Controllers;

use App\public_holiday;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
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
        $public_holiday = public_holiday::paginate(10);
        
        return $public_holiday;
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
        $data = request()->validate([
            'description' => 'required',
            'date' => 'required'
        ]);

        public_holiday::create($data);

        return ['message' => 'Public Holiday created!'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\public_holiday  $public_holiday
     * @return \Illuminate\Http\Response
     */
    public function show(public_holiday $public_holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\public_holiday  $public_holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(public_holiday $public_holiday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\public_holiday  $public_holiday
     * @return \Illuminate\Http\Response
     */
    
     public function update(Request $request, public_holiday $public_holiday)
    {

        $data = request()->validate([
            'description' => 'required',
            'date' => 'required'
        ]);

      //  $public_holiday = public_holiday::find(request()->id);

        $public_holiday->update($data);

        return['message' => 'Public Holiday Updated!'];
    } 

/*     public function update(Request $request, $id)
    {
        //$public_holiday = public_holiday::find($id);

       //  $data = request()->validate([
      //      'description' => 'required',
       //     'date' => 'required'
       // ]);

       

      //  $public_holiday->description = $request->description; 
       // $public_holiday->date = $request->date; 

        //$public_holiday->update();


        return['message' => 'Public Holiday Updated!'];
    } */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\public_holiday  $public_holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(public_holiday $public_holiday)
    {
        $public_holiday->delete();

        return ['message' => 'Public Holiday Deleted!'];
    }
}
