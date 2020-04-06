<?php

namespace App\Http\Controllers;
use App\Leave;
use App\User;
use DB;
use App\public_holiday;
use App\Department;
use App\Category;
use App\Comment;
use App\Mail\LeaveApprovalMail;
use App\Mail\LeaveStatusChange;
use App\Mail\ConfirmRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->id() == 1)
        {
            return Leave::where('status', 3)->orderBy('id', 'desc')->paginate(20);
        }
        else
        {
            return Leave::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(20); 
        }
        
    }
    public function getUsers(){
        return User::whereNotIn('id', [1, auth()->id()])->select('id', 'firstname', 'lastname')->get();
    }
    public function getAllUsers(){
        return User::where('id', '>', 1)->select('id', 'firstname', 'lastname')->get();
    }
    public function getHolidays(){
        return public_holiday::pluck("date");
    }
    public function getApprovals(){
        return User::where('permission', '=', TRUE)->whereNotIn('id', [1, auth()->id()])->select('id', 'firstname', 'lastname')->get();
    }

    public function getUserApprovalRequests()
    {
        // select all leave approvals for a particular hod or approval id
        return Leave::where('approval_id', auth()->id())->orderBy('id', 'desc')->paginate(20);
    }

    public function leaveApproval(Request $request, $id)
    {
       // $leave = Leave::find($id);

        $data = request()->validate([
            'status' => 'sometimes',
            'comment' => 'sometimes',
           
        ]);

        $comment = ($request->input('comment'));
        
        $leave = Leave::findorfail($id);
      
        $leave->update($data);

        $lastID = $leave->id;

        // save comments ...
        if($comment)
        {
            $saveCom = new Comment();
            $saveCom->user_id = $leave->user_id;
            $saveCom->approval_id = $leave->approval_id;
            $saveCom->leave_id = $lastID;
            $saveCom->comment = $comment;

            $saveCom->save();
        }


        // send an email to user & admin...

        $userdata = User::Find($leave->approval_id);

        $userdata1 = User::Find($leave->user_id);
        $when = now()->addMinutes(10);
 
        Mail::to($userdata1->email)->cc('hr.cal@ecmterminals.com')->later($when, new ConfirmRequest($userdata, $userdata1, $leave, $comment));

        if($request->input('status') == 3)
        {
            return ['message' => 'Leave Application Approved!'];

        }else{

            return ['message' => 'Leave Application Rejected!'];

            
        }

        
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
        
        $this->validatedData();

        if($this->checkLeavestatus(auth()->id()))
        {
            if($this->sameDate(auth()->id(), $request->start_date))
            {
                if($this->outstandingDays(auth()->id(), $request->year, $request->days))
                {
                    $this->getOutstanding(auth()->id(), $request->year, $request->days);

                    $leave = new Leave();

                    $leave->start_date = request('start_date');
                    $leave->days = request('days');
                    $leave->outsdays = $this->outs;
                    $leave->year = request('year');
                    $leave->leave_type = request('leave_type');
                    $leave->duty_reliever = request('duty_reliever');
                    $leave->approval_id = request('approval_id');
                    $leave->user_id = auth()->id();
                    $leave->status = request('status');

                    $leave->save();

                    $userdata = User::Find(auth()->id());
                    
                    $email = User::where('id', $leave->approval_id)->pluck('email');
                    $when = now()->addMinutes(10);

                    Mail::to($email)->later($when, new LeaveApprovalMail($userdata));

                    return ['message' => 'Leave Application successfully saved!'];
                    
                }
                else{
                    return ['message' => 'You have exceed approved days!'];
                }
            }
            else {
                return ['message' => 'Leave entry already exist!'];
            }
        }
        else{
            return ['message' => 'You have either an open or pending or rejected Leave Application.'];
        }
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
        //
        $this->validatedData();

        $leave = Leave::findorfail($id);

        $this->getOutstanding(auth()->id(), $request->year, $request->days);

        $leave->start_date = request('start_date');
        $leave->days = request('days');
        $leave->outsdays = $this->outs;
        $leave->year = request('year');
        $leave->leave_type = request('leave_type');
        $leave->duty_reliever = request('duty_reliever');
        $leave->approval_id = request('approval_id');
        $leave->user_id = auth()->id();
        $leave->status = request('status');

        if($request->start_date == $request->oldstartdate)
        {
            if($this->outstandingDays4Update(auth()->id(), $request->year, $request->days, $request->olddays))
            {
          
                $leave->update();

                $userdata = User::Find(auth()->id());
                    
                $email = User::where('id', $leave->approval_id)->pluck('email');

                $when = now()->addMinutes(10);

               Mail::to($email)->later($when, new LeaveApprovalMail($userdata));

               return ['message' => 'Leave Application successfully updated!'];
            }
            else{
                return ['message' => 'You have exceed approved days!'];
            }
        }
        else{

            if($this->sameDate(auth()->id(), $request->start_date))
            {
                if($this->outstandingDays4Update(auth()->id(), $request->year, $request->days, $request->olddays))
                {
                    $leave->update();

                    $userdata = User::Find(auth()->id());
                    
                    $email = User::where('id', $leave->approval_id)->pluck('email');
                    
                    $when = now()->addMinutes(10);

                    Mail::to($email)->later($when, new LeaveApprovalMail($userdata));

                    return ['message' => 'Leave Application successfully updated!'];
                }
                else{
                    return ['message' => 'You have exceed approved days!'];
                }
            }
            else {
                    return ['message' => 'Leave entry already exist!'];
            }
        }
    }

    public function statusOpen(Request $request, $id)
    {
        $leave = Leave::find($id);

        $leaveid = Leave::where([
            ['user_id', '=', $leave->user_id], 
            ['status', '=',3], 
            ['year', '=', $leave->year]
            ])->orderBy('id', 'desc')
            ->first();

        if($leaveid->id == $id)
        {
            if(Leave::where([['user_id', '=', $leave->user_id], ['status', '<', 3]])->count() == 0)
            {
                $leave->status = request('status');

                $leave->update();

                $userdata = User::find($leave->user_id);
                    
                $admin = auth()->user()->email;

                $when = now()->addMinutes(10);

                Mail::to($userdata->email)->later($when, new LeaveStatusChange($userdata, $admin, $leave));
                return ['message' => 'Leave Application successfully opened!'];
            }else{
                return ['message' => 'User has either open, pending or rejected leave form!'];
            }
        }else{
            return ['message' => 'Only the last applied leave entry for a particular leave year can be opened!'];
        }


 

        
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

     //leave summary method
    public function leaveSummary()
    {
        // retrieve a user leave summary
        return DB::table('leaves')->select('year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where('user_id', auth()->id())->groupBy('year')->paginate(20); 
    
    }

    public function leaveSum()
    {
        // retrieve all users leave summary
        return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->groupBy('user_id','year')->paginate(20);

    } 

    
    private function getOutstanding($userid, $year, $curdays)
    {
        //get outstanding days if any
        $outsday = Leave::where(['user_id' => $userid, 'year' => $year, 'status' => '3'])->orderBy('id', 'desc')->pluck('outsdays')->first();

        // approved days
        $approved_days = User::find($userid)->category->days;

        $this->outs = $outsday ? $outsday - $curdays : $approved_days - $curdays;

        return $this->outs;
    }

    // data validation method..
    private function validatedData()
    {
       return request()->validate([
            'start_date' => 'required',
            'days' => 'required|max:30|min:1',
            
            'year' => 'required|numeric|min:2006|max:2050',
            'leave_type' => 'required',
            'duty_reliever' => 'required',
            'approval_id' => 'required',

            'status' => 'required',
        ]);

    }
    
    // Check for existing leave that is pending or rejected..
    private function checkLeavestatus($userid)
    {
        //$result = Leave::where(['user_id' => $staffid, 'status' => 0])->orwhere('status', 1)->count();
        $result = DB::table('leaves')->where([
            ['user_id', '=', $userid],
            ['status', '<>', '3']
        ])->count();

        return $result == 0 ? true : false ;
    }

     // Outstanding leave days for update method
     private function outstandingDays4Update($userid, $year, $curdays, $oldday)
     {
 
         $days_utilized = Leave::where(['user_id' => $userid, 'year' => $year, 'status' => '3'])->groupBy('year')->sum('days');
 
         // approved days
         $approved_days = User::find($userid)->category->days;

        // outstanding days
        $outsday = Leave::where(['user_id' => $userid, 'year' => $year, 'status' => '3'])->orderBy('id', 'desc')->pluck('outsdays')->first();
        
        if($outsday != null)
        {
            $result = ( $outsday >= $curdays ) ? true : false;
        }
        else
        {
            $result = ($approved_days >=  $curdays) ? true : false;
        }
        
        return $result;
 
        //return $result = $approved_days >= ($days_utilized + $curdays) ? true : false;
     }

     // Check if user id has applied for the same start date ....
     private function sameDate($userid, $startdate)
     {
         $result = Leave::where(['user_id' => $userid, 'start_date' => $startdate])->count();
         return $result == 0 ? true : false ;
     }
 
     // Outstanding leave days for a particular year...
     private function outstandingDays($userid, $year, $curdays)
     {

        // days utilized
        $days_utilized = Leave::where(['user_id' => $userid, 'year' => $year])->groupBy('year')->sum('days');

        // approved days
        $approved_days = User::find($userid)->category->days;
        
        // outstanding days
        $outsday = Leave::where(['user_id' => $userid, 'year' => $year])->orderBy('id', 'desc')->pluck('outsdays')->first();
        
        if($outsday != null)
        {
            $result = ( $outsday >= $curdays ) ? true : false;
        }
        else
        {
            $result = ($approved_days >=  $curdays) ? true : false;
        }
        
        return $result;
     }

     public function filterAppliedLeave($id, $year)
     {
         // return leave approved by user id and year
        if(auth()->id() == 1){

            return Leave::where([['user_id', '=', $id], ['year', '=', $year], ['status', '=', 3]])->orderBy('id', 'desc')->paginate(20); 
        }else{
            return Leave::where([['user_id', '=', $id], ['year', '=', $year], ['approval_id', '=', auth()->id()]])->orderBy('id', 'desc')->paginate(20); 

        }
     }
  
     public function filterAppliedLeaveByYear($year)
     {
         // return leave approved by year
        if(auth()->id() == 1){

            return Leave::where([['year', '=', $year] ,['status', '=', 3]])->orderBy('id', 'desc')->paginate(20); 

        }else{
            return Leave::where([['year', '=', $year], ['approval_id', '=', auth()->id()]])->orderBy('id', 'desc')->paginate(20); 

        }
     }
     public function filterAppliedLeaveByUser($id)
     {
         // return leave approved by user id
        if(auth()->id() == 1){

            return Leave::where([['user_id', '=', $id],['status', '=', 3]])->orderBy('id', 'desc')->paginate(20); 
        }else{
            return Leave::where([['user_id', '=', $id], ['approval_id', '=', auth()->id()]])->orderBy('id', 'desc')->paginate(20); 

        }
     }
    public function filterLeaveSum($id, $year)
    {
        // retrieve leave summary by user id and year
        if(auth()->id() == 1){
            return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where([['user_id', '=', $id], ['year', '=', $year]])->groupBy('user_id','year')->paginate(20);

        }else{
            return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where([['user_id', '=', $id], ['year', '=', $year], ['approval_id', '=', auth()->id()]])->groupBy('user_id','year')->paginate(20);

        }
    }

    public function filterLeaveSumByYear($year)
    {
        // retrieve leave summary by user id and year
        if(auth()->id() == 1){
            return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where('year', '=', $year)->groupBy('user_id','year')->paginate(20);

        }else{
            return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where([['year', '=', $year], ['approval_id', '=', auth()->id()]])->groupBy('user_id','year')->paginate(20);

        }
    }
    public function filterLeaveSumByUser($id)
    {
        // retrieve leave summary by user id and year
        if(auth()->id() == 1){
            return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where('user_id', '=', $id)->groupBy('user_id','year')->paginate(20);

        }else{
            return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where([['user_id', '=', $id], ['approval_id', '=', auth()->id()]])->groupBy('user_id','year')->paginate(20);

        }
    }

    public function ApprovalStaffSum()
    {
        // retrieve all approval staff leave summary
        return DB::table('leaves')->select('user_id', 'year', DB::raw('sum(days) as days'), DB::raw('min(outsdays) as outsdays'))->where('approval_id', '=', auth()->id())->groupBy('user_id','year')->paginate(20);

    }

    public function getViewApprovalRequests()
    {
        // select all leave approvals for a particular hod or approval id
        return Leave::where(['approval_id' => auth()->id(), 'status' => 1])->orderBy('id', 'desc')->paginate(20);
    }
}
