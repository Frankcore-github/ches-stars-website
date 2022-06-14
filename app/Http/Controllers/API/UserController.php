<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
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
        return User::where('id','<>',auth('api')->user()->id)->orderBy('usertype')->paginate(25);
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
            'username'=> 'required|string|max:20|unique:users',
            'usertype'=> 'required|string|max:7|min:5',
            'password'=> 'required|string|max:255|min:5',
            'repeat_password' => 'sometimes|same:password|string'
        ]);
        return User::create([
            'username' => $request['username'],
            'usertype' => $request['usertype'],
            'password' => Hash::make($request['repeat_password'])
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
       $this->authorize('isAdmin');
        $usertype = User::findOrFail($id)->usertype;
        return User::findOrFail($id)->$usertype;
    }

    public function profileImage()
    {
        $usertype = User::findOrFail(auth('api')->user()->id)->usertype;
        return User::findOrFail(auth('api')->user()->id)->$usertype->photo;
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
        $user = User::findOrFail($id);
        $this->validate($request,[
            'username'=> 'required|string|max:20|unique:users,username,'.$user->id,
            'password'=> 'sometimes|required|string|max:255|min:5',
            'repeat_password' => 'sometimes|required|same:password|string'
        ]);
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->update($request->all());
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
        $user = User::findOrFail($id);
        // if($user->usertype === 'staff'){
        //     $userPhoto = storage_path('app/public/img/profile/'.$user->staff->photo);
        //     @unlink($userPhoto);
        //     $user->staff()->delete();
        // }
        // else if($user->usertype === 'student'){
        //     $userPhoto = storage_path('app/public/img/profile/'.$user->student->photo);
        //     @unlink($userPhoto);
        //     $user->student()->delete();
        // }
        $user->delete();
    }
}
