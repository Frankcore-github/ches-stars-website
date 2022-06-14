<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SubjectList;

class SubjectListController extends Controller
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
        return SubjectList::oldest()->paginate(10);
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
            'class_name'=> ['required','string','max:20'],
            'subject_name'=> ['required', 'string', 'max:30'],
            'background_color' => ['required', 'string', 'max:10'],
            'staff_id' => ['required', 'integer']
        ]);
        return SubjectList::create([
            'class_name' => $request['class_name'],
            'subject_name' => $request['subject_name'],
            'background_color' => $request['background_color'],
            'staff_id' => $request['staff_id']
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewSubjects($class)
    {
        $this->authorize('isAdmin');
        return SubjectList::with('staff')->Where('class_name', $class)->paginate(10);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubjects()
    {
        $this->authorize('isStudent');
        $class = auth('api')->user()->student->class;
        return SubjectList::Where('class_name', $class)->get();
        //return $class;
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
        $subject = SubjectList::findOrFail($id);
        $this->validate($request,[
            'class_name'=> ['required','string','max:20'],
            'subject_name'=> ['required', 'string', 'max:30'],
            'background_color' => ['required', 'string', 'max:10'],
            'staff_id' => ['required', 'integer']
        ]);
        $subject->update($request->all());
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
        $subject = SubjectList::findOrFail($id);
        $subject->delete();
    }
}
