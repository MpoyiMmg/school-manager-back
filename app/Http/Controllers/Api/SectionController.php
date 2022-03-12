<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Section;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    public function index()
    {
        return Section::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        {  // validate data from request
            $validated = Validator::make($request->all(), [
                'designation' => 'required|max:60',
                'short' => 'required|max:6',
            ]);
    
            if ($validated->fails()) {
                return response()->json([$validated->errors()], 422);
            }
    
            $newSection = Section::create($request->all());
            return response()->json($newSection, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Presence  $presence
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section, $id)
    {    
        return Section::find($id);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Presence  $presence
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Presence  $presence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'designation' => 'required|max:60',
            'short' => 'required|max:5',
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()]);
        }
        
        $section = Section::findOrFail($id);
        $section->update($request->all());
        
        return response()->json($section, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Presence  $presence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section, $id)
    {
        $section->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
