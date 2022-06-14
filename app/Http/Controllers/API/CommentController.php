<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Events\CommentEvent;
use Illuminate\Http\Request;
use App\Note;
use App\Comment;
use App\Assignment;

class CommentController extends Controller
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
        if($request->has('note_id')){
            $this->validate($request,[
                'note_id'=> ['required', 'integer'],
                'body'=> ['required', 'string']
            ]);
    
            $comment = Note::findOrFail($request['note_id'])->comments()->create([
                'body' => $request['body'],
                'user_id'=>  auth('api')->user()->id
            ]);
    
            $note = Note::findOrFail($request->note_id)->comments()->with([
                'user' => function($query) {
                    $query->select('id');
                },'user.staff'=> function($query){
                    $query->select('user_id','first_name', 'middle_name','last_name','photo');
                },'user.student'=> function($query){
                    $query->select('user_id','first_name', 'middle_name','last_name','photo');
                }
            ])->latest()->get();
            broadcast(new CommentEvent($note->toArray(), $request->note_id));    
        }
        else if($request->has('assignment_id')){
            $this->validate($request,[
                'assignment_id'=> ['required', 'integer'],
                'body'=> ['required', 'string']
            ]);
    
            $comment = Assignment::findOrFail($request['assignment_id'])->comments()->create([
                'body' => $request['body'],
                'user_id'=>  auth('api')->user()->id
            ]);
    
            $assignment = Assignment::findOrFail($request->assignment_id)->comments()->with([
                'user' => function($query) {
                    $query->select('id');
                },'user.staff'=> function($query){
                    $query->select('user_id','first_name', 'middle_name','last_name','photo');
                },'user.student'=> function($query){
                    $query->select('user_id','first_name', 'middle_name','last_name','photo');
                }
            ])->latest()->get();
            broadcast(new CommentEvent($assignment->toArray(), $request->assignment_id));
        }
       
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNote($id)
    {
        $note = Note::findOrFail($id)->comments()->with([
            'user' => function($query) {
                $query->select('id');
            }, 'user.staff'=> function($query){
                $query->select('user_id','first_name', 'middle_name','last_name','photo');
            },'user.student'=> function($query){
                $query->select('user_id','first_name', 'middle_name','last_name','photo');
            }
        ])->latest()->get();
        $user = auth('api')->user()->id;

        $count = Note::findOrFail($id)->comments()->count();
        return response()->json(['object1' => $note , 'object2' => $count, 'user' => $user]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAssignment($id)
    {
        $assignment = Assignment::findOrFail($id)->comments()->with([
            'user' => function($query) {
                $query->select('id');
            }, 'user.staff'=> function($query){
                $query->select('user_id','first_name', 'middle_name','last_name','photo');
            },'user.student'=> function($query){
                $query->select('user_id','first_name', 'middle_name','last_name','photo');
            }
        ])->latest()->get();
        $user = auth('api')->user()->id;
        $count = Assignment::findOrFail($id)->comments()->count();
        return response()->json(['object1' => $assignment , 'object2' => $count, 'user' => $user]);
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
       
        $this->validate($request,[
            'body'=> ['required', 'string']
        ]);
        $comment = Comment::find($id);
        if(auth('api')->user()->id == $comment->user_id){
            $comment->body = $request->body;
            $comment->save();
            if($comment->commentable_type=="App\Note"){
                $note = Note::findOrFail($comment->commentable_id)->comments()->with([
                    'user' => function($query) {
                        $query->select('id');
                    },'user.staff'=> function($query){
                        $query->select('user_id','first_name', 'middle_name','last_name','photo');
                    },'user.student'=> function($query){
                        $query->select('user_id','first_name', 'middle_name','last_name','photo');
                    }
                ])->latest()->get();
                broadcast(new CommentEvent($note->toArray(), $comment->commentable_id));
            }
            else if($comment->commentable_type=="App\Assignment"){
                $assignment = Assignment::findOrFail($comment->commentable_id)->comments()->with([
                    'user' => function($query) {
                        $query->select('id');
                    },'user.staff'=> function($query){
                        $query->select('user_id','first_name', 'middle_name','last_name','photo');
                    },'user.student'=> function($query){
                        $query->select('user_id','first_name', 'middle_name','last_name','photo');
                    }
                ])->latest()->get();
                broadcast(new CommentEvent($assignment->toArray(), $comment->commentable_id));
            }
        }else return "Unauthorized";
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $note = Note::findOrFail($comment->commentable_id)->comments()->with([
            'user' => function($query) {
                $query->select('id');
            },'user.staff'=> function($query){
                $query->select('user_id','first_name', 'middle_name','last_name','photo');
            },'user.student'=> function($query){
                $query->select('user_id','first_name', 'middle_name','last_name','photo');
            }
        ])->latest()->get();
        broadcast(new CommentEvent($note->toArray(), $comment->commentable_id));
        $comment->delete();
    }
}
