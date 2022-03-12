<?php

namespace App\Http\Controllers\Api;

use App\Models\Option;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $options = Option::all()->map(function($option) {
            $section = Section::find($option->section_id);

            return [
                "id" => $option->id,
                "designation" => $option->designation,
                "short" => $option->short,
                "section" => $section
            ];
        });
        return $options;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'designation' => "required|max:30|unique:options,designation",
            'short' => "required|max:5|unique:options,short",
            'sectionId' => "required"
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }
        $option = [
            'designation' => $request->designation,
            'short' => $request->short,
            'section_id' => $request->sectionId
        ];
        $newOption = Option::create($option);
        return response()->json($newOption, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function show(Option $option, $id)
    {
        $option = $option->find($id);
        $section = Section::find($option->section_id);

        return [
            "designation" => $option->designation,
            "short" => $option->short,
            "section" => [
                "id" => $section->id,
                "designation" => $section->designation
            ],
            "id" => $option->id
        ];
    }

    public function update(Request $request, $id)
    {
        // validate data from request
        $validated = Validator::make($request->all(), [
            'designation' => "max:30",
            'short' => "max:5"
        ]);


        if ($validated->fails()) {
            return response()->json([$validated->errors()]);
        }
        
        $option = Option::findOrFail($id);
        $option->update($request->all());
        
        return response()->json($option, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function destroy(Option $option, $id)
    {
        $option->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
