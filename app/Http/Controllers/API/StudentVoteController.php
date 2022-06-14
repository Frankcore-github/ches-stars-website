<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StudentVote;
use App\Student;
class StudentVoteController extends Controller
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
            'name'=> 'required|string|max:100',
            'class_name'=> 'required|string',
            'vote' => 'required|string'
        ]);
        return StudentVote::create([
            'user_id' => auth('api')->user()->id,
            'name' => $request['name'],
            'class_name' => $request['class_name'],
            'vote' => $request['vote'],
        ]);
    }

    public function getVotes($class){
        $this->authorize('isAdmin');
        $total =  Student::where('class',$class)->count();
        $yesCount = StudentVote::where('class_name',$class)->where('vote', "YES")->count();
        $noCount = StudentVote::where('class_name',$class)->where('vote', "NO")->count();
        $remainingVote = $total - ($yesCount + $noCount);
        $yesStudents = StudentVote::where('class_name',$class)->where('vote', "YES")->get();
        $noStudents = StudentVote::where('class_name',$class)->where('vote', "NO")->get();
        return response()->json([
            'yesVotes' => $yesCount,
            'noVotes' => $noCount,
            'remainingVotes' => $remainingVote,
            'yesStudents' => $yesStudents,
            'noStudents' => $noStudents
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
}
