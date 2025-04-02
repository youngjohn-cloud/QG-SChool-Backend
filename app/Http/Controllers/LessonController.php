<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Qg_Class;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    // CRUD FOR THE LESSON MODEL
    public function lessonList()
    {
        $lesson = Lesson::with('teacher', 'subject', 'class')->get();
        return response()->json($lesson, 200);
    }
    public function getRelatedData()
    {
        $relatedData = [
            'teacher' => Teacher::select('id', 'firstname', 'lastname')->get(),
            'subject' => Subject::select('id', 'name')->get(),
            'qg_class' => Qg_Class::select('id', 'name')->get(),
        ];
        return response()->json($relatedData, 200);
    }
    public function createLesson(Request $request)
    {
        try {
            $lesson =   new Lesson([
                'name' => $request->input('name'),
                'day' => $request->input('day'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
            ]);
            $lesson->subject_id =  $request->input('subject_id');
            $lesson->class_id =  $request->input('class_id');
            $lesson->teacher_id =  $request->input('teacher_id');
            $lesson->load('subject', 'class', 'teacher');
            $lesson->save();
            return response()->json($lesson, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => 'error creating lesson', 'error' => $th->getMessage()], 500);
        }
    }
    public function updateLesson($id, Request $request)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json(['message' => "Lesson not found"], 404);
        }
        try {
            $lesson->update([
                'name' => $request->input('name'),
                'day' => $request->input('day'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'subject_id' =>  $request->input('subject_id'),
                'class_id' =>  $request->input('class_id'),
                'teacher_id' =>  $request->input('teacher_id'),
            ]);
            return response()->json(["message" => "Lesson updated Succesfully"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Lesson update failed", "error" => $th->getMessage()], 500);
        }
    }
    public function deleteLesson($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json(['message' => "Lesson not found"], 404);
        }
        $lesson->delete();
        return response(null, 200);
    }
}
