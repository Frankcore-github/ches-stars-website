<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SSLC;

class SSLCController extends Controller
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
        return SSLC::oldest()->paginate(10);
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
            'no_of_student'=> ['required','integer', 'max:100'],
            'no_of_passed_student'=> ['required','integer', 'max:100'],
            'year'=> ['required', 'integer', 'digits:4', 'max:'.(date('Y')+1)]
        ]);
        return SSLC::create([
            'no_of_student' => $request['no_of_student'],
            'no_of_passed_student' => $request['no_of_passed_student'],
            'year' => $request['year']
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
        $sslc = SSLC::findOrFail($id);
        $this->validate($request,[
            'no_of_student'=> ['required','integer','max:100'],
            'no_of_passed_student'=> ['required','integer', 'max:100'],
            'year'=> ['required', 'integer', 'digits:4', 'max:'.(date('Y')+1)]
        ]);
       
        $sslc->update($request->all());
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
        $event = SSLC::findOrFail($id);
        $event->delete();
    }
}
