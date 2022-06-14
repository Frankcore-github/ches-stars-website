<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\NewsFeed;

class NewsFeedController extends Controller
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
        return NewsFeed::oldest()->paginate(10);
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
            'file_caption'=> ['required','string','max:50']
        ]);
        if($request->file){
            $name = time().'.'.$request->extension;
            $explode = explode(';', $request->file);
            $file = explode(',', $explode[1]);
            file_put_contents(storage_path('app/public/file/newsfeed/').$name, base64_decode($file[1]));
            $request->merge(['file'=>$name]);
        }
        return NewsFeed::create([
            'file_name' => $request['file'],
            'file_caption' => $request['file_caption']
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
        $this->authorize('isAdmin');
        $newsfeed = NewsFeed::findOrFail($id);
        $file = storage_path('app/public/file/newsfeed/'.$newsfeed->file_name);
        @unlink($file);
        $newsfeed->delete();
    }
}
