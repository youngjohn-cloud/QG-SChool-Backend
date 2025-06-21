<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Lesson;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    // assignment list 
    public function assignmentList()
    {
        $assignments = Assignment::with('lesson.subject', 'lesson.class', 'lesson.teacher')->get();
        return response()->json($assignments, 200);
    }


    // CRUD PROCESS FOR THE ASSIGNMENT MODELS
    public function createAssignment(Request $request)
    {
        try {
            $assignment = new Assignment([
                'title' => $request->input('title'),
                'start_date' => $request->input('start_date'),
                'due_date' => $request->input('due_date'),
            ]);
            $assignment->lesson_id = $request->input('lesson_id');
            $assignment->load('lesson');
            $assignment->save();
            return response()->json($assignment, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error creating Assignment', 'error' => $th->getMessage()], 500);
        }
    }
    public function updateAssignment($id, Request $request)
    {
        $assigmnent = Assignment::find($id);
        if (!$assigmnent) {
            return response()->json(['message' => "assignment not found"], 404);
        }
        try {
            $assigmnent->update([
                'title' => $request->input('title'),
                'start_date' => $request->input('start_date'),
                'due_date' => $request->input('due_date'),
                'lesson_id' => $request->input('lesson_id'),
            ]);
            return response()->json(null, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Updating assigmnent failed', 'error' => $th->getMessage()]);
        }
    }
    public function deleteAssignment($id)
    {
        $assigmnent = Assignment::find($id);
        if (!$assigmnent) {
            return response()->json(['message' => 'assignment not found'], 404);
        }
        $assigmnent->delete();
        return response(null, 200);
    }
    public function getAssignmentRelatedData()
    {
        $relatedData = Lesson::select('id', 'name')->get();
        return response()->json($relatedData, 200);
    }
}
