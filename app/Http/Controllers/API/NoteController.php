<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\NoteUploadedEvent;
use App\Events\NotificationEvent;
use App\Student;
use App\Note;
use App\User;

class NoteController extends Controller
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
        $this->authorize('all');
        $this->validate($request,[
            'class_name'=> ['required','string','max:20'],
            'subject_name'=> ['required','string','max:40'],
            'notes_text'=> ['required','string'],
            'url'=> ['nullable','string', 'max:65535'],
            'chapter_name'=> ['required','string','max:60'],
            'extension' => ['nullable','in:pdf,PDF'],
        ]);

        $user = User::findOrFail(auth('api')->user()->id);
        $name = $user->staff->first_name.' '.$user->staff->last_name;
        $filename='';
        if($request->file){
            $filename = time().'.'.$request->extension;
            $explode = explode(';', $request->file);
            $file = explode(',', $explode[1]);
            file_put_contents(storage_path('app/public/classroom/note/').$filename, base64_decode($file[1]));
            $request->merge(['file'=>$filename]);
            /*
            $path = $request->file('file')->store('notes', 's3'); 
            $fileName = basename($path);
            $url = Storage::disk('s3')->url($path);
            */
        }
            $uploaded = $user->notes()->create([
            'class_name' => $request['class_name'],
            'subject_name' => $request['subject_name'],
            'notes_text' => $request['notes_text'],
            'file_name' => $request['file'],
            'url' => $request['url'],
            'chapter_name' => $request['chapter_name'],
            'user_id' => auth('api')->user()->id
            ]); 
            
            broadcast(new NoteUploadedEvent())->toOthers();
            $this->noteNotify($request['class_name'], $request['subject_name'], $name);
            broadcast(new NotificationEvent())->toOthers();
            
    }

    public function noteNotify($class, $subject, $name)
    {
            $students = Student::all()->where('class', $class);
            $message = collect(['subject' => $subject , 'message' => $name.' | '.$subject.' notes']);
            Notification::send($students, new NewNotification($message));
    }  
    
    public function showNotifications()
    {
        $usertype = auth('api')->user()->usertype;
        $count = auth('api')->user()->$usertype->unreadNotifications->count();
        return response()->json(['object1' => auth('api')->user()->$usertype->unreadNotifications, 'object2' => $count]);
    } 
    
    public function markAllAsRead()
    {
        $usertype = auth('api')->user()->usertype;
        return auth('api')->user()->$usertype->unreadNotifications()->update(['read_at' => now()]);
    }

    public function markAsRead($id)
    {
        $usertype = auth('api')->user()->usertype;
        $notification =  auth('api')->user()->$usertype->unreadNotifications()->find($id);
        return $notification->markAsRead();
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
        return Note::findOrFail($id)->file_name;
    }

    public function showNotes($class, $subject, $val = 5)
    {
        $this->authorize('all');
        $user = User::findOrFail(auth('api')->user()->id);
        $count = $user->notes()->where('class_name', $class)->where('subject_name', $subject)->count();
        $note = $user->notes()->where('class_name', $class)->where('subject_name', $subject)->latest()->paginate($val);
        
        return response()->json(['object1' => $note, 'object2' => $count]);
    }

    public function studentNotes($subject)
    {
        $this->authorize('isStudent');
        $class = auth('api')->user()->student->class;
        $count =  Note::where('class_name', $class)->where('subject_name', $subject)->count();
        $note = Note::where('class_name', $class)->where('subject_name', $subject)->latest()->paginate(5);

        return response()->json(['object1' => $note, 'object2' => $count]);
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
        $note = Note::findOrFail($id);
        $this->validate($request,[
            'class_name'=> ['required','string','max:20'],
            'subject_name'=> ['required','string','max:40'],
            'notes_text'=> ['required','string'],
            'url'=> ['nullable','string', 'max:65535'],
            'chapter_name'=> ['required','string','max:60'],
            'extension' => ['nullable','in:pdf,PDF'],
        ]);
        $note->update($request->all());
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
        $note = Note::findOrFail($id);
        if($note->file_name){
           // Storage::disk('s3')->delete('notes/'.$note->file_name);
           $file = storage_path('app/public/classroom/note/'.$note->file_name);
           @unlink($file);

        }
        $note->comments()->delete();
        $note->delete();
    }

}
    