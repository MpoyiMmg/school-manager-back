<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Professor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    /**
     * Display all courses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $couses = Course::all()->map(function ($course) {
            $professor = Professor::find($course->professor_id);
            return [
                'id' => $course->id,
                'designation' => $course->designation,
                'ponderation' => $course->ponderation,
                'professor' => [
                    'id' => $professor->id,
                    'fullname' => "$professor->name $professor->last_name $professor->first_name"
                ]
            ];
        });
        return $couses;
    }

    /**
     *Store a new course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'designation' => "required|max:30|unique:courses,designation",
            'ponderation' => "required|max:5|",
            'professorId' => "required"
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }
        $course = [
            'designation' => $request->designation,
            'ponderation' => $request->ponderation,
            'professor_id' => $request->professorId
        ];
        $newCourse = Course::create($course);
        return response()->json($newCourse, 201);
    }

    /**
     * Display course in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course, $id)
    {
        $course = $course->find($id);
        $professor = Professor::find($course->professor_id);

        return [
            "designation" => $course->designation,
            "ponderation" => $course->ponderation,
            'professor' => [
                'id' => $professor->id,
                'fullname' => "$professor->name $professor->last_name $professor->first_name"
            ],
            "id" => $course->id
        ];
    }


    /**
     * Update Course in storage .
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate data from request
        $validated = Validator::make($request->all(), [
            'designation' => "max:30",
            'ponderation' => "max:5"
        ]);


        if ($validated->fails()) {
            return response()->json([$validated->errors()]);
        }

        $course = Course::findOrFail($id);
        $course->update($request->all());

        return response()->json($course, 200);
    }

    /**
     * Remove or delete course in storage .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, $id)
    {
        $course->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
