<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
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
        return Event::oldest()->paginate(10);
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
            'event_name'=> ['required','string'],
            'event_position'=> ['required', 'string', 'max:50'],
            'event_year'=> ['required', 'integer', 'digits:4','min:1980', 'max:'.(date('Y')+1)],
        ]);
        return Event::create([
            'event_name' => $request['event_name'],
            'event_position' => $request['event_position'],
            'event_year' => $request['event_year'],
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
        $event = Event::findOrFail($id);
        $this->validate($request,[
            'event_name'=> ['required','string'],
            'event_position'=> ['required', 'string', 'max:50'],
            'event_year'=> ['required', 'integer', 'digits:4', 'max:'.(date('Y')+1)]
        ]);
        
        $event->update($request->all());
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
        $event = Event::findOrFail($id);
        $event->delete();
    }
}
