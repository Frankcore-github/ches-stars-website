<?php

namespace App\Http\Controllers\API;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;

class StudentController extends Controller
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
        $this->authorize('isAdmin');
        return Student::oldest()->paginate(10);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewStudents($class)
    {
        
        $this->authorize('all');
        return Student::oldest()->Where('class', $class)->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->authorize('isAdmin');
        $this->validate($request,[
            'first_name'=> ['required','string','max:20'],
            'midddle_name'=> ['nullable', 'string', 'max:20'],
            'last_name'=> ['required', 'string', 'max:20'],
            'class'=> ['required', 'string', 'max:20'],
            'address'=> ['nullable', 'string'],
            'phoneno'=> ['nullable', 'integer'],
            'examstatus'=> ['required', 'string'],
            'user_id'=> ['required', 'integer']
        ]);
        // if($request->photo){
        //     // $name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';'))))[1][1];
        //      $name = time().'.'.$request->extension;
        //     Image::make($request->photo)->resize(300, 300)->save(storage_path('app/public/img/profile/').$name);
        //     $request->merge(['photo'=>$name]);
        // }
        return Student::create([
            'first_name' => $request['first_name'],
            'midddle_name' => $request['midddle_name'],
            'last_name' => $request['last_name'],
            'class' => $request['class'],
            'address' => $request['address'],
            'phoneno' => $request['phoneno'],
            // 'photo' => $request['photo'],
            'photo' => null,
            'examstatus' => $request['examstatus'],
            'user_id' => $request['user_id']
            ]); 
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
        $this->authorize('isAdmin');
        $student = Student::findOrFail($id);
        $this->validate($request,[
            'first_name'=> 'required|string|max:20',
            'middle_name'=> 'nullable|string|max:20',
            'last_name'=> 'required|string|max:20',
            'class' => 'required|string|max:20',
            'address' => 'nullable|string',
            'phoneno' => 'nullable|integer',
            'examstatus' => 'required|string',
            'user_id' => 'required|integer|unique:students,user_id,'.$student->id
        ]);
        $currentPhoto = $student->photo;
        if($request->photo != $currentPhoto){
            // $name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';'))))[1][1];
             $name = time().'.'.$request->extension;
            Image::make($request->photo)->resize(300, 300)->save(storage_path('app/public/img/profile/').$name);
            $request->merge(['photo'=>$name]);
            $userPhoto = storage_path('app/public/img/profile/'.$currentPhoto);
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        $student->update($request->all());
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
