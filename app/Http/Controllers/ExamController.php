<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Lesson;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // method for the exam list and eager load the lesson class
    public function examList()
    {
        $exam = Exam::with('lesson.subject', 'lesson.class', 'lesson.teacher')->get();
        return response()->json($exam, 200);
    }
    // CRUD PROCESS FOR THE EXAM MODEL
    public function createExam(Request $request)
    {
        try {
            $exam = new Exam([
                'title' => $request->input('title'),
                'startTime' => $request->input('start_time'),
                'endTime' => $request->input('end_time'),
            ]);
            $exam->lesson_id = $request->input('lesson_id');
            $exam->load('lesson');
            $exam->save();
            return response()->json($exam, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error creating Exam', 'error' => $th->getMessage()], 500);
        }
    }
    public function updateExam($id, Request $request)
    {
        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['message' => "Exam not found"], 404);
        }
        try {
            $exam->update([
                'title' => $request->input('title'),
                'startTime' => $request->input('start_time'),
                'endTime' => $request->input('end_time'),
                'lesson_id' => $request->input('lesson_id'),
            ]);
            return response()->json(null, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Updating exam failed', 'error' => $th->getMessage()]);
        }
    }
    public function deleteExam($id)
    {
        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }
        $exam->delete();
        return response(null, 200);
    }
    public function getExamsRelatedData()
    {
        $relatedData = Lesson::select('id', 'name')->get();
        return response()->json($relatedData, 200);
    }
}
