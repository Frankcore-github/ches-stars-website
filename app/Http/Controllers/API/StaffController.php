<?php

namespace App\Http\Controllers\API;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Staff;
use App\User;
use App\Note;
class StaffController extends Controller
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
        return Staff::oldest()->paginate(10);
    }

    public function allStaff()
    {
        $this->authorize('isAdmin');
        return Staff::all();
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
            'middle_name'=> ['nullable', 'string', 'max:20'],
            'last_name'=> ['required', 'string', 'max:20'],
            'classes'=> ['nullable', 'string'],
            'address'=> ['nullable', 'string'],
            'phoneno'=> ['nullable', 'integer'],
            'user_id'=> ['required', 'integer','unique:staff']
        ]);
        if($request->photo){
             $name = time().'.'.$request->extension;
            Image::make($request->photo)->resize(300, 300)->save(storage_path('app/public/img/profile/').$name);
            $request->merge(['photo'=>$name]);
        }
        return Staff::create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'classes' => $request['classes'],
            'address' => $request['address'],
            'phoneno' => $request['phoneno'],
            'photo' => $request['photo'],
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

    }

    public function getStaffClasses($id)
    {
        $this->authorize('isAdmin');
        return  User::findOrFail($id)->staff->subjectslist->pluck('class_name','id')->unique()->toJson();
    }

    public function getStaffSubjects($id,$classname)
    {
        $this->authorize('isAdmin');
        return Note::select(\DB::raw('count(*) as notes_count, subject_name, class_name'))->groupBy('subject_name','class_name')->where('user_id', $id)->where('class_name',$classname)->get();
    }

    public function getStaffNotes($id,$classname,$subjectname)
    {
        $this->authorize('isAdmin');
        return Note::where('user_id', $id)->where('class_name',$classname)->where('subject_name',$subjectname)->paginate(10);
    }

    public function showClasses()
    {
        $this->authorize('isStaff');
        return User::find(auth('api')->user()->id)->staff->subjectslist()->groupBy('class_name')->selectRaw('count(*) as total,class_name')->get();
    }

    public function showClassesAndSubjects()
    {
        $this->authorize('isStaff');
        $user_id = auth('api')->user()->id;
        return User::find(auth('api')->user()->id)->staff->subjectslist;
    }

    public function subjects($class)
    {
        $this->authorize('isStaff');
        $user_id = auth('api')->user()->id;
        return User::find(auth('api')->user()->id)->staff->subjectslist()->where('class_name', $class)->get();
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
        $staff = Staff::findOrFail($id);
        $this->validate($request,[
            'first_name'=> 'required|string|max:20',
            'middle_name'=> 'nullable|string|max:20',
            'last_name'=> 'required|string|max:20',
            'classes'=> 'nullable|string',
            'address'=> 'nullable|string',
            'phoneno'=> 'nullable|integer',
            'user_id' => 'required|integer|unique:staff,user_id,'.$staff->id
        ]);
        $currentPhoto = $staff->photo;
        if($request->photo != $currentPhoto){
            $name = time().'.'.$request->extension;
            Image::make($request->photo)->resize(300, 300)->save(storage_path('app/public/img/profile/').$name);
            $request->merge(['photo'=>$name]);
            $userPhoto = storage_path('app/public/img/profile/'.$currentPhoto);
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }        
        $staff->update($request->all());
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
