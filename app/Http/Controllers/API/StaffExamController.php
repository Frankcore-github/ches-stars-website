<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StudentExam;
use App\StaffExam;
use App\User;
class StaffExamController extends Controller
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
        $this->authorize('isStaff');

        if(StaffExam::where('class_name', $request['class_name'])->where('subject_name',$request['subject_name'])->count() > 0){
            return response()->json(['errors'=> true]);
        }

        $this->validate($request,[
            'class_name'=> ['required','string'],
            'subject_name'=> ['required','string'],
            'exam_text'=> ['required','string'],
            'file_name'=> ['required','string'],
            'extension' => ['nullable','in:pdf,PDF'],
        ],['file_name.required' => 'Please select a file.']);
        $user = User::findOrFail(auth('api')->user()->id);
        $fileName = '';
        if($request->file){
            $filename = time().'.'.$request->extension;
            $explode = explode(';', $request->file);
            $file = explode(',', $explode[1]);
            file_put_contents(storage_path('app/public/classroom/exam/').$user->id.$filename, base64_decode($file[1]));
            $request->merge(['file'=>$user->id.$filename]);
            // $path = $request->file('file')->store('assignments', 's3');
            // $fileName = basename($path);
            // $url = Storage::disk('s3')->url($path);
        }
             StaffExam::create([
            'class_name' => $request['class_name'],
            'subject_name' => $request['subject_name'],
            'exam_text' => $request['exam_text'],
            'file_name' => $request['file'],
            'user_id' => $user->id
            ]); 
            return StaffExam::where('class_name',  $request['class_name'])->where('subject_name', $request['subject_name'])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('all');
        return StaffExam::findOrFail($id);
    }

    public function showQuestionPapers($classname, $subjectname)
    {
        $this->authorize('all');
        // return StaffExam::where('class_name', $classname)->where('subject_name', $subjectname)->get();
         return response()->json([
            'staffexam'=> StaffExam::where('class_name', $classname)->where('subject_name', $subjectname)->get(),
            'studentsexam' => StudentExam::with('student')->where('class_name',$classname,)->where('subject_name',$subjectname)->get()
         ]);
    }

    public function showStudentPapers($classname, $subjectname)
    {
        $this->authorize('all');
        return StudentExam::with('student')->where('class_name',$classname,)->where('subject_name',$subjectname)->get();
        // return response()->json([
        //     'exam'=> StudentExam::with('student')->where('class_name',$classname,)->where('subject_name',$subjectname),
        //     'students' => Student::where('class',$classname)
        //  ]);

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
        $this->authorize('isStaff');
        $exam = StaffExam::findOrFail($id);
        if($exam->file_name){
           // Storage::disk('s3')->delete('exam/'.$exam->file_name);
           $file = storage_path('app/public/classroom/exam/'.$exam->file_name);
           @unlink($file);
        }
        $exam->delete();
    }
}
