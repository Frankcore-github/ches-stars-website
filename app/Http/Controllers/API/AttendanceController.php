<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Attendance;
use App\User;

class AttendanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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


    public function setAttendance($subject, $noteid)
    {
        $this->authorize('isStudent');
        $user = User::findOrFail(auth('api')->user()->id);
        if (Attendance::where('class_name', $user->student->class)->where('subject_name', $subject)->where('student_id', $user->student->id)->where('note_id', $noteid)->exists()) {
            return response()->json(['message' => 'exist']);
         }
         Attendance::create([
            'class_name' => $user->student->class,
            'subject_name' => $subject,
            'student_id' => $user->student->id,
            'note_id' => $noteid,
        ]);
        return response()->json(['message' => 'success']); 
    }

    public function countAttendance($studentid, $subject, $classname)
    {
        $this->authorize('isStaff');
        $user = User::findOrFail(auth('api')->user()->id);
        $countnotes = $user->notes()->where('class_name', $classname)->where('subject_name', $subject)->count();
        $countattendance = Attendance::where('student_id', $studentid)->where('subject_name',$subject)->where('class_name', $classname)->count();
        return response()->json(['notes' => $countnotes, 'attendance' => $countattendance]); 
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
