<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\ClassTeacher;
use App\Gallery;
use App\Event;
use App\SSLC;
use App\Notice;
use App\NewsFeed;
class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function gallery()
    {
        return Gallery::select(DB::raw('CONCAT("/storage/img/gallery/",image_name) as src'), 'image_caption as title')->paginate(6); 
    }

    public function about()
    {
        return Event::orderBy('event_year', 'asc')->get();
    }

    public function academics()
    {
        return SSLC::orderBy('year', 'asc')->get();
    }

    public function home()
    {
       $notice = Notice::oldest()->get();
       $newsFeed = NewsFeed::oldest()->get();
       return response()->json(['notice' => $notice, 'newsfeed' => $newsFeed]);
    }

    public function staff()
    {
        return ClassTeacher::select('first_name','middle_name','last_name', 'class')->get(); 
    }

    public function email()
    {
         //$to_name = "ches'stars sechondary school";
         //$to_email = "chesstars2018@gmail.com";
        // $data = array('name' => 'peter', 'body' => 'test email');
         Mail::raw('This is the content of mail body', function ($message) {
            $message->from('chesstars2018@gmail.com', 'Social Team');
            $message->to('chesstars2018@gmail.com');
            $message->subject('App - Forget Password');
        });
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
