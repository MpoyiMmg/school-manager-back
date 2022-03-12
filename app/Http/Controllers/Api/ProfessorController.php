<?php

namespace App\Http\Controllers\Api;

use App\Models\Professor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        return Professor::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { {  // validate data from request
            $validated = Validator::make($request->all(), [
                'name' => 'required',
                'last_name' => 'required',
                'first_name' => 'required',
                'birthday' => 'required',
                'place_birth_day' => 'required',
                'nationality' => 'required',
                'state' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'adress' => 'required',

            ]);

            if ($validated->fails()) {
                return response()->json([$validated->errors()], 422);
            }

            $newProfessor = Professor::create($request->all());
            return response()->json($newProfessor, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function show(Professor $professor, $id)
    {
        return Professor::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Http\Response
     */

    public function untenured()
    {
        return [
            'hello' => "world"
        ];
    }
    public function update(Request $request, Professor $professor, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'birthday' => 'required',
            'place_birth_day' => 'required',
            'nationality' => 'required',
            'state' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'adress' => 'required',

        ]);
        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }

        $professor = Professor::findOrFail($id);
        $professor->update($request->all());

        return response()->json($professor, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professor $professor, $id)
    {
        $professor->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
