<?php

namespace App\Http\Controllers\Api;

use App\Models\Option;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all()->map(function ($student) {
            $classroom = Classroom::findOrFail($student->classroom_id);
            $option = Option::findOrFail($classroom->option_id);

            return [
                "fullname" => $student->middlename . " " . $student->lastname . " " . $student->firstname,
                "classroom" => $classroom->level . " " . $option->designation . " " . $classroom->part,
                "tutor" => $student->tutor,
                "contact_student" => $student->contact_student,
                "contact_tutor" => $student->contact_tutor,
                "id" => $student->id
            ];
        });

        return $students;
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
            'firstname' => "required|max:25",
            'middlename' => "required|max:25",
            'lastname' => "required|max:25",
            'classroom_id' => "required",
            'tutor' => "required|max:25",
            'contact_tutor' => "required|max:25",
            'contact_student' => "required|max:25|unique:students"
        ]);

        if ($validated->fails()) {
            return response()->json([
                "type" => "Credentials Error",
                "message" => $validated->errors()
            ], 422);
        }

        $newStudent = Student::create($request->all());
        return response()->json($newStudent, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student, $id)
    {
        $student = $student->findOrFail($id);
        $classroom = Classroom::findOrFail($student->classroom_id);
        $option = Option::findOrFail($classroom->option_id);

        return response()->json([
            "middlename" => $student->middlename,
            "firstname" =>  $student->firstname,
            "lastname" =>  $student->lastname,
            "classroom" => [
                "id" => $classroom->id,
                "classroom" => "$classroom->level $option->designation $classroom->part"
            ],
            "tutor" => $student->tutor,
            "contact_student" => $student->contact_student,
            "contact_tutor" => $student->contact_tutor,
            "id" => $student->id
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'firstname' => "required|max:25",
            'middlename' => "required|max:25",
            'lastname' => "required|max:25",
            'classroom_id' => "required",
            'tutor' => "required|max:25",
            'contact_student' => "required|max:25",
            'contact_tutor' => "required|max:25"
        ]);

        if ($validated->fails()) {
            return response()->json([
                "type" => "Credentials Error",
                "message" => $validated->errors()
            ], 422);
        }
        $student = Student::findOrFail($id);
        $student->update($request->all());
        return response()->json($student, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student, $id)
    {
        $student->findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
