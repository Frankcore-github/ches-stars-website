<?php

namespace App\Http\Controllers\API;

use App\ClassTeacher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassTeacherController extends Controller
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
        return ClassTeacher::oldest()->paginate(10);
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
            'first_name'=> 'required|string|max:20|',
            'middle_name'=> 'nullable|string|min:0|max:20',
            'last_name'=> 'required|string|max:20',
            'class' => 'required|string|max:20'
        ]);
        return ClassTeacher::create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'class' => $request['class'],
            
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
        $classTeacher = ClassTeacher::findOrFail($id);
        $this->validate($request,[
            'first_name'=> 'required|string|max:20|',
            'middle_name'=> 'nullable|string|min:0|max:20',
            'last_name'=> 'required|string|max:20',
            'class' => 'required|string|max:20'
        ]);
        $classTeacher->update($request->all());
        return ['message' => "User updated successfully"];
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
        $classTeacher = ClassTeacher::findOrFail($id);
        $classTeacher->delete();
    }
}
