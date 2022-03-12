<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
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
    public function index($id)
    {
        $classromSchedules = Schedule::where('classroom_id', $id)
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'course' => [
                        'id' => $schedule->course->id,
                        'designation' => $schedule->course->designation
                    ],
                    'classroom' => [
                        'id' => $schedule->classroom->id,
                        'designation' => $schedule->classroom->level . " " . $schedule->classroom->option->designation . " " . $schedule->classroom->part
                    ],
                ];
            });

        return  $classromSchedules;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'course_id' => 'required',
            'day_of_week' => 'required',
            'hour_start_time' => "required",
            'min_start_time' => "required",
            'hour_end_time' => "required",
            'min_end_time' => "required"
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }

        //  verification if the start and end hours
        if ((intval($request->hour_start_time) < 0 || intval($request->hour_start_time) >= 23) ||
            (intval($request->hour_end_time) < 0 || intval($request->hour_end_time) > 23)
        ) {
            return response()->json([
                'error' => 'The hour must be between 0 and 23'
            ], 422);
        }

        //  verification if the start and end minutes
        if ((intval($request->min_start_time) < 0 || intval($request->min_start_time) > 59) ||
            (intval($request->min_end_time) < 0 || intval($request->min_end_time) > 59)
        ) {
            return response()->json([
                'error' => 'The minute must be between 0 and 59'
            ], 422);
        }

        // verify if start time is less than end time
        if (intval($request->hour_start_time) > intval($request->hour_end_time)) {
            return response()->json([
                'error' => 'The start time must be less than end time'
            ], 422);
        }

        $newClassSchedule = Schedule::create([
            'course_id' => $request->course_id,
            'classroom_id' => $id,
            'day_of_week' => $request->day_of_week,
            'start_time' => "$request->hour_start_time:$request->min_start_time",
            'end_time' => "$request->hour_end_time:$request->min_end_time"
        ]);
        return response()->json($newClassSchedule, 201);
    }

    /**
     * verify the start and end time
     * @param $start_time
     * @param $end_time
     * 
     * @return true
     */
    private function verifyTimes($start_time, $end_time)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        $schedule = Schedule::findOrFail(request()->scheduleId);

        return [
            'id' => $schedule->id,
            'day_of_week' => $schedule->day_of_week,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'course' => [
                'id' => $schedule->course->id,
                'designation' => $schedule->course->designation
            ],
            'classroom' => [
                'id' => $schedule->classroom->id,
                'designation' => $schedule->classroom->level . " " . $schedule->classroom->option->designation . " " . $schedule->classroom->part
            ],
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'course_id' => 'required',
            'day_of_week' => 'required',
            'hour_start_time' => "required",
            'min_start_time' => "required",
            'hour_end_time' => "required",
            'min_end_time' => "required"
        ]);

        if ($validated->fails()) {
            return response()->json([$validated->errors()], 422);
        }

        //  verification if the start and end hours
        if ((intval($request->hour_start_time) < 0 || intval($request->hour_start_time) >= 23) ||
            (intval($request->hour_end_time) < 0 || intval($request->hour_end_time) > 23)
        ) {
            return response()->json([
                'error' => 'The hour must be between 0 and 23'
            ], 422);
        }

        //  verification if the start and end minutes
        if ((intval($request->min_start_time) < 0 || intval($request->min_start_time) > 59) ||
            (intval($request->min_end_time) < 0 || intval($request->min_end_time) > 59)
        ) {
            return response()->json([
                'error' => 'The minute must be between 0 and 59'
            ], 422);
        }

        // verify if start time is less than end time
        if (intval($request->hour_start_time) > intval($request->hour_end_time)) {
            return response()->json([
                'error' => 'The start time must be less than end time'
            ], 422);
        }

        $scheduleEdit = Schedule::find(request()->scheduleId);
        $scheduleEdit->update([
            'course_id' => $request->course_id,
            'classroom_id' => $id,
            'day_of_week' => $request->day_of_week,
            'start_time' => "$request->hour_start_time:$request->min_start_time",
            'end_time' => "$request->hour_end_time:$request->min_end_time"
        ]);
        return $scheduleEdit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->findOrFail(request()->scheduleId)->delete();
        return response()->json(null, 204);
    }
}
