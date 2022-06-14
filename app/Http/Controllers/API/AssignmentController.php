<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewNotification;
use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SubjectList;
use App\Assignment;
use App\Staff;
use App\User;
class AssignmentController extends Controller
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
        $this->authorize('isStudent');
        $this->validate($request,[
            'subject_name'=> ['required','string'],
            'chapter_name'=> ['required','string','max:60'],
            'extension' => ['nullable','in:pdf,PDF'],
        ]);
        $user = User::findOrFail(auth('api')->user()->id);
        $class = $user->student->class;
        $name = $user->student->first_name.' '.$user->student->last_name;
        $url = ''; 
        $fileName = '';
        if($request->file){
            $filename = time().'.'.$request->extension;
            $explode = explode(';', $request->file);
            $file = explode(',', $explode[1]);
            file_put_contents(storage_path('app/public/classroom/assignment/').$filename, base64_decode($file[1]));
            $request->merge(['file'=>$filename]);
            // $path = $request->file('file')->store('assignments', 's3'); 
            // $fileName = basename($path);
            // $url = Storage::disk('s3')->url($path);  
            $user->assignments()->create([
                'class_name' => auth('api')->user()->student->class,
                'subject_name' => $request['subject_name'],
                'file_name' => $request['file'],
                'url' => $url,
                'chapter_name' => $request['chapter_name'],
                'user_id' => $user->id
                ]); 
                $this->assignmentNotify($class, $request['subject_name'], $name);
                broadcast(new NotificationEvent())->toOthers();
        }
            
    }

    public function assignmentNotify($class, $subject, $name)
    {   
        $temp = SubjectList::where('class_name', $class)->where('subject_name', $subject)->first();
        $staff = Staff::findOrFail($temp->staff_id);
        $message = collect(['message' => $subject.' by '.$name.' | '.ucfirst($class),'subject' => $subject, 'class' => $class]);
        Notification::send($staff, new NewNotification($message));
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

    public function selfAssignment($subject)
    {
        $this->authorize('isStudent');
        $count =  auth('api')->user()->assignments()->where('subject_name', $subject)->count();
        $assignment = auth('api')->user()->assignments()->where('subject_name', $subject)->latest()->paginate(5);
        return response()->json(['object1' => $assignment, 'object2' => $count]);
    }


    
    public function showAssignment($class, $subject)
    {
        $this->authorize('isStaff');
        $count =  Assignment::where('class_name', $class)->where('subject_name', $subject)->count();
        $assignment = Assignment::with([
            'user' => function($query) {
                $query->select('id');
            },'user.student'=> function($query){
                $query->select('user_id','first_name', 'middle_name','last_name');
            }
        ])->where('class_name', $class)->where('subject_name', $subject)->latest()->paginate(5);
        return response()->json(['object1' => $assignment, 'object2' => $count]);
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
        $this->authorize('isStudent');
        $assignment = Assignment::findOrFail($id);
        if($assignment->file_name){
           // Storage::disk('s3')->delete('assignments/'.$assignment->file_name);
           $file = storage_path('app/public/classroom/assignment/'.$assignment->file_name);
           @unlink($file);
        }
        $assignment->comments()->delete();
        $assignment->delete();
    }
}
