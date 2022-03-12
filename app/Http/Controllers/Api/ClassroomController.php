<?php

namespace App\Http\Controllers\Api;

use App\Models\Option;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classrooms = Classroom::all()->map(function ($classroom) {
            $option = Option::find($classroom->option_id);
            $section = Section::find($option->section_id);
            return [
                "designation" => $classroom->level . " " . $option->designation . " " . $classroom->part,
                "titulary" => "The Big",
                "local" => "local",
                "option" => [
                    "id" => $option->id,
                    "designation" => $option->designation,
                    "short" => $option->short,
                    "section" => $section
                ],
                "id" => $classroom->id
            ];
        });
        return $classrooms;
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
            'part' => "required|max:1",
            'level' => "required|max:1",
            'option_id' => "required"
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }

        $newClassroom = Classroom::create($request->all());
        return response()->json($newClassroom, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom, $id)
    {
        $classroom = $classroom->findOrFail($id);
        $option = Option::find($classroom->option_id);
        $section = Section::find($option->section_id);

        return [
            "designation" => $classroom->level . " " . $classroom->option->designation . " " . $classroom->part,
            "titulary" => "The Big",
            "level" => $classroom->level,
            "part" => $classroom->part,
            "local" => "local",
            "option" => [
                "id" => $option->id,
                "designation" => $option->designation,
            ],
            "id" => $classroom->id
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'part' => "required|max:1",
            'level' => "required|max:1",
            'option_id' => "required"
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }

        $classroom = Classroom::findOrFail($id);
        if (isset($request->professor_id)) {
            $classroom->professor_id = $request->professor_id;
        }
        $classroom->update($request->all());

        return response()->json([
            "designation" => $classroom->level . " " . $classroom->option->designation . " " . $classroom->part,
            "titulary" => "The Big",
            "level" => $classroom->level,
            "part" => $classroom->part,
            "local" => "local",
            "option" => [
                "id" => $classroom->option->id,
                "designation" => $classroom->option->designation,
            ],
            "id" => $classroom->id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom, $id)
    {
        $classroom->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
