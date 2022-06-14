<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AllClass;
class AllClassController extends Controller
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
        return AllClass::all();
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
            'class'=> 'required|string',
            'class_name'=> 'required|string',
        ]);
        return AllClass::create([
            'class' => $request['class'],
            'class_name' => $request['class_name']
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
        $allClass = AllClass::findOrFail($id);
        $this->validate($request,[
            'class'=> 'required|string',
            'class_name'=> 'required|string'
        ]);
        $allClass->update($request->all());
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
        $allClass = AllClass::findOrFail($id);
        $allClass->delete();
    }
}
