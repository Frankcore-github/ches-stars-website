<?php

namespace App\Http\Controllers;
use App\Events\NoteUploadedEvent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function home()
    {
        return view('dashboard');
    }

     public function index()
    {
        return view('index');
    }

    
}
