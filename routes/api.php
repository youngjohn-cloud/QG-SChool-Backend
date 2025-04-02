<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QgClassController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
// CRUD  Route || api for Parents
Route::get('userList', [UserListController::class, 'userList']);
Route::post('/createParent', [UserListController::class, 'createParent']);
Route::patch('updateParent/{id}', [UserListController::class, 'updateParent']);
Route::delete('deleteParent/{id}', [UserListController::class, 'deleteParent']);

// Route || api  for the teacher registration, login and list show a single teacher 
Route::get('teachersList', [TeacherController::class, 'teachersList']);
Route::get('/showTeacher/{id}', [TeacherController::class, 'showTeacher']);
Route::get('getTeachersList', [TeacherController::class, 'getTeachers']);
Route::post('createTeacher', [TeacherController::class, 'createTeacher']);
Route::patch('updateTeacher/{id}', [TeacherController::class, 'updateTeacher']);
Route::delete('deleteTeacher/{id}', [TeacherController::class, 'deleteTeacher']);

// Route || api  for the student registration, login and list 
Route::get('studentList', [StudentController::class, 'studentList']);
Route::get('getStudentRelatedData', [StudentController::class, 'getStudentRelatedData']);
Route::post('createStudent', [StudentController::class, 'createStudent']);
Route::get('/showStudent/{id}', [StudentController::class, 'showStudent']);
Route::patch('updateStudent/{id}', [StudentController::class, 'updateStudent']);
Route::delete('deleteStudent/{id}', [StudentController::class, 'deleteStudent']);



// Route || api for the lesson List and register
Route::get('lessonList', [LessonController::class, 'lessonList']);
Route::get('/getLessonRelatedData', [LessonController::class, 'getRelatedData']);
Route::post('/createLesson', [LessonController::class, 'createLesson']);
Route::patch('/updateLesson/{id}', [LessonController::class, 'updateLesson']);
Route::delete('/deleteLesson/{id}', [LessonController::class, 'deleteLesson']);


// Route || api for the subject Register, List and register
Route::get('getSubjects', [SubjectController::class, 'getSubjects']);
Route::get('subjectList', [SubjectController::class, 'subjectList']);
Route::post('subjectRegister', [SubjectController::class, 'subjectRegister']);
Route::patch('/subjectUpdate/{id}', [SubjectController::class, 'subjectUpdate']);
Route::delete('/subjectDelete/{id}', [SubjectController::class, 'subjectDelete']);



// Route || api for the class List and register
Route::get('classList', [QgClassController::class, 'classList']);
Route::post('classRegister', [QgClassController::class, 'classRegister']);
Route::patch('/updateClass/{id}', [QgClassController::class, 'updateClass']);
Route::delete('/classDelete/{id}', [QgClassController::class, 'classDelete']);

// Route || api for the Exam List and register
Route::get('examList', [ExamController::class, 'examList']);
// Route || api for the Assignment List and register
Route::get('assignmentList', [AssignmentController::class, 'assignmentList']);
// Route || api for the Assignment List and register
Route::get('resultList', [ResultController::class, 'resultList']);
// Route || api for the Event List
Route::get('EventsList', [EventController::class, 'EventsList']);
// Route || api for the Announcement List
Route::get('announcementList', [AnnouncementController::class, 'announcementList']);
