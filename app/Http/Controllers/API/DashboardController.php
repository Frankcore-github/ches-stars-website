<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Staff;
use App\Student;
use App\Event;
use App\Notice;
use App\ClassTeacher;
use App\Gallery;
use App\NewsFeed;
use App\SSLC;
use App\Assignment;
use App\Note;
use App\SubjectList;
use App\StudentExam;
use App\StudentVote;
class DashboardController extends Controller
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

    public function adminDashboard()
    {
        $this->authorize('isAdmin');
        $admin = User::where('usertype','admin')->count();
        $staff = User::where('usertype','staff')->count();
        $student = User::where('usertype','student')->count();
        $event = Event::count();
        $notice = Notice::count();
        $classTeacher = ClassTeacher::count();
        $gallery = Gallery::count();
        $newsFeed = NewsFeed::count();
        $sslc = SSLC::count();

        return response()->json([
            'admin' => $admin, 
            'staff' => $staff, 
            'student' => $student, 
            'event' => $event, 
            'notice' => $notice,
            'classTeacher' => $classTeacher,
            'gallery' => $gallery,
            'newsFeed' => $newsFeed,
            'sslc' => $sslc,
            ]);
    }

    public function staffDashboard()
    {
        $this->authorize('isStaff');
        $assignment = 0;
        $id = User::findOrFail(auth('api')->user()->id)->staff->id;
        $photo = User::findOrFail(auth('api')->user()->id)->staff->photo;
        $fname = User::findOrFail(auth('api')->user()->id)->staff->first_name;
        $mname = User::findOrFail(auth('api')->user()->id)->staff->midle_name;
        $lname = User::findOrFail(auth('api')->user()->id)->staff->last_name;
        $fullName = $fname.' '.$mname.' '.$lname;
        $class = SubjectList::where('staff_id', $id)->distinct()->get('class_name')->count();
        $subject = SubjectList::where('staff_id', $id)->count();
        $note = Note::where('user_id',auth('api')->user()->id)->count();
        $vars = User::find(auth('api')->user()->id)->staff->subjectslist()->groupBy('class_name','subject_name')->selectRaw('class_name, subject_name')->get();
        foreach ($vars as $var){
           $assignment += Assignment::where('class_name', $var->class_name)->where('subject_name',$var->subject_name)->count();
        }


        return response()->json([
            'name' => $fullName,
            'photo' => $photo,
            'class' => $class, 
            'subject' => $subject,
            'note' => $note,
            'assignment' => $assignment,
        ]);
    }

    public function studentDashboard()
    {
        $this->authorize('isStudent');
        $assignment = 0;
        $class = User::findOrFail(auth('api')->user()->id)->student->class;
        $photo = User::findOrFail(auth('api')->user()->id)->student->photo;
        $fname = User::findOrFail(auth('api')->user()->id)->student->first_name;
        $mname = User::findOrFail(auth('api')->user()->id)->student->midle_name;
        $lname = User::findOrFail(auth('api')->user()->id)->student->last_name;
        $votes = StudentVote::where('user_id',auth('api')->user()->id)->count();
        $fullName = $fname.' '.$mname.' '.$lname;
        $subject = SubjectList::Where('class_name', $class)->count();
        $assignment = Assignment::where('user_id',auth('api')->user()->id)->count();
        $note = Note::where('class_name',$class)->count();


        return response()->json([
            'name' => $fullName,
            'photo' => $photo,
            'class' => ucwords($class), 
            'subject' => $subject,
            'assignment' => $assignment,
            'note' => $note,
            'vote'=> $votes
        ]);
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


    public function searchUser()
    {
        $this->authorize('isAdmin');
        if($search = \Request::get('query')){
            $users = User::where(function($query) use ($search){
                $query->where('username', 'LIKE', "%$search%")
                    ->orWhere('usertype', 'LIKE', "%$search%");
            })->paginate(100);
        }
        return $users;
    }

    public function searchStudent()
    {
        $this->authorize('isAdmin');
        if($search = \Request::get('query')){
            $student = Student::where(function($query) use ($search){
                $query->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('middle_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%");
            })->paginate(100);
        }
        return $student;
    }

    public function searchStaff()
    {
        $this->authorize('isAdmin');
        if($search = \Request::get('query')){
            $staff = Staff::where(function($query) use ($search){
                $query->where('first_name', 'LIKE', "%$search%")
                ->orWhere('middle_name', 'LIKE', "%$search%")
                ->orWhere('last_name', 'LIKE', "%$search%");
            })->paginate(100);
        }
        return $staff;
    }

    public function searchSubject()
    {
        $this->authorize('isAdmin');
        if($search = \Request::get('query')){
            $subject = SubjectList::where(function($query) use ($search){
                $query->where('subject_name', 'LIKE', "%$search%")
                ->Where('class_name',  \Request::get('class'));
            })->paginate(100);
        }
        return $subject;
    }

    public function searchExam()
    {
        $this->authorize('isAdmin');
        if($search = \Request::get('query')){
            $exam = StudentExam::where(function($query) use ($search){
                $query->where('student_id', $search);
            })->get();

            $student = Student::where(function($query) use ($search){
                $query->where('id', $search);
            })->get();
        }
        return response()->json([
            'student' => $student,
            'exam' => $exam
        ]);
    }
}
