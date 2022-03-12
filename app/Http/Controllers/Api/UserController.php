<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * list all users
     * 
     * @param \Illuminate\Http\Response $request.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return User::all();
    }

    /**
     * Store a new user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate data from request
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }

        $newUser = User::create($request->all());
        return response()->json($newUser, 201);
    }

    /**
     * Display a specific user.
     *
     * @param \App\Models\User $user
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $id)
    {
        return User::find($id);
    }


    /**
     * Update the specif user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate data from request
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()]);
        }
        
        $user = User::findOrFail($id);
        $user->update($request->all());
        
        return response()->json($user, 200);
    }

    /**
     * Remove a specific user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, $id)
    {
        $user->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
