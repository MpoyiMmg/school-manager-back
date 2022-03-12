<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ProfessorController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ScheduleController;

Route::post("register", [AuthenticationController::class, "register"]);
Route::post('signin', [AuthenticationController::class, "signin"]);

// Route::middleware('auth:sanctum')->get('/user', [UserController::class, "index"]);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{userId}', [UserController::class, 'show']);
    Route::put('/users/{userId}', [UserController::class, 'update']);
    Route::delete('/users/{userId}', [UserController::class, 'destroy']);
    Route::post('/signout', [AuthenticationController::class, 'signout']);

    // sections
    Route::get('/sections', [SectionController::class, 'index']);
    Route::post('/sections', [SectionController::class, 'store']);
    Route::get('/sections/{sectionId}', [SectionController::class, 'show']);
    Route::put('/sections/{sectionId}', [SectionController::class, 'update']);
    Route::delete('/sections/{sectionId}', [SectionController::class, 'destroy']);

    //professors
    Route::get('/professors', [ProfessorController::class, 'index']);
    Route::post('/professors', [ProfessorController::class, 'store']);
    Route::get('/professors/{professorId}', [ProfessorController::class, 'show']);
    Route::put('/professors/{professorId}', [ProfessorController::class, 'update']);
    Route::delete('/professors/{professorId}', [ProfessorController::class, 'destroy']);
    Route::get('/professors/untenured', [ProfessorController::class, 'getProfessor']);
    // options
    Route::get('/options', [OptionController::class, 'index']);
    Route::post('/options', [OptionController::class, 'store']);
    Route::get('/options/{optionId}', [OptionController::class, 'show']);
    Route::put('/options/{optionId}', [OptionController::class, 'update']);
    Route::delete('/options/{optionId}', [OptionController::class, 'destroy']);

    // classrooms
    Route::get('/classrooms', [ClassroomController::class, 'index']);
    Route::post('/classrooms', [ClassroomController::class, 'store']);
    Route::get('/classrooms/{classroomId}', [ClassroomController::class, 'show']);
    Route::put('/classrooms/{classroomId}', [ClassroomController::class, 'update']);
    Route::delete('/classrooms/{classroomId}', [ClassroomController::class, 'destroy']);

    // Schedules
    Route::get('/classrooms/{classromId}/schedules', [ScheduleController::class, 'index']);
    Route::get('/classrooms/{classromId}/schedules/{scheduleId}', [ScheduleController::class, 'show']);
    Route::post('/classrooms/{classromId}/schedules', [ScheduleController::class, 'store']);
    Route::put('/classrooms/{classromId}/schedules/{scheduleId}', [ScheduleController::class, 'update']);
    Route::delete('/classrooms/{classromId}/schedules/{scheduleId}', [ScheduleController::class, 'destroy']);


    // students
    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::get('/students/{studentId}', [StudentController::class, 'show']);
    Route::put('/students/{studentId}', [StudentController::class, 'update']);
    Route::delete('/students/{studentId}', [StudentController::class, 'destroy']);

    // courses
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{courseId}', [CourseController::class, 'show']);
    Route::put('/courses/{courseId}', [CourseController::class, 'update']);
    Route::delete('/courses/{courseId}', [CourseController::class, 'destroy']);
});
