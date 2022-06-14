<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StudentExam;
use App\StaffExam;
use App\User;
class StudentExamController extends Controller
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
        $user = User::findOrFail(auth('api')->user()->id);
        $class = $user->student->class;
        $this->validate($request,[
            'subject_name'=> ['required','string'],
            'file_name'=> ['required','string'],
            'extension' => ['nullable', 'in:pdf,PDF'],
        ],['file_name.required' => 'Please select a file.']);
        $fileName = '';
        if($request->file){
            $filename = time().'.'.$request->extension;
            $explode = explode(';', $request->file);
            $file = explode(',', $explode[1]);
            file_put_contents(storage_path('app/public/classroom/exam/').$user->id.$filename, base64_decode($file[1]));
            $request->merge(['file'=>$user->id.$filename]);
            StudentExam::create([
                'class_name' => $class,
                'subject_name' => $request['subject_name'],
                'file_name' => $request['file'],
                'student_id' => $user->student->id
                ]); 
                return response()->json(['answerscript' => $request['file']]);
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
        $this->authorize('all');
        return StudentExam::findOrFail($id)->file_name;
    }

    public function showAnswerPapers($classname, $subjectname)
    {
        $this->authorize('all');
        return StudentExam::where('class_name', $classname)->where('subject_name', $subjectname)->get();
    }

    public function showQuestionPapers($subjectname)
    {
        $this->authorize('isStudent');
        $user = User::findOrFail(auth('api')->user()->id);
        $class = $user->student->class;

        return response()->json([
            'questionpaper'=> StaffExam::where('class_name', $class)->where('subject_name', $subjectname)->get(),
            'answerpaper' => StudentExam::where('class_name',$class)->where('subject_name',$subjectname)->where('student_id',$user->student->id)->pluck('file_name'),
            'status' => StudentExam::where('class_name',$class)->where('subject_name',$subjectname)->where('student_id',$user->student->id)->pluck('status')
         ]);
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
        $this->authorize('isStaff');
        $exam = StudentExam::findOrFail($id);
        $exam->update([
            'status' => 'seen'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $exam = StudentExam::findOrFail($id);
        if($exam->file_name){
           // Storage::disk('s3')->delete('exam/'.$exam->file_name);
           $file = storage_path('app/public/classroom/exam/'.$exam->file_name);
           @unlink($file);
        }
        $exam->delete();
    }
}
