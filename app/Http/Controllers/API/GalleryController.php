<?php

namespace App\Http\Controllers\API;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Gallery;

class GalleryController extends Controller
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
        return Gallery::oldest()->paginate(6);
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
            'image_caption'=> ['sometimes','string','max:20']
        ]);
        if($request->photo){
            // $name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';'))))[1][1];
             $name = time().'.'.$request->extension;
            Image::make($request->photo)->save(storage_path('app/public/img/gallery/').$name);
            $request->merge(['photo'=>$name]);
        }
        return Gallery::create([
            'image_name' => $request['photo'],
            'image_caption' => $request['image_caption']
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
        $image = Gallery::findOrFail($id);
        $photo = storage_path('app/public/img/gallery/'.$image->image_name);
        @unlink($photo);
        $image->delete();
    }
}
