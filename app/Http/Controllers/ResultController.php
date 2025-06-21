<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Exam;
use App\Models\Result as ModelsResult;
use App\Models\Student;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    // fetch all results
    public function resultList()
    {
        $examResults = ModelsResult::with([
            'student' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'exam.lesson.teacher' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'exam.lesson.class' => fn($q) => $q->select('id', 'name'),
        ])->whereNotNull('exam_id')->get();

        // Fetch remaining results with an assignment (no exam)
        $assignmentResults = ModelsResult::with([
            'student' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'assignment.lesson.teacher' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'assignment.lesson.class' => fn($q) => $q->select('id', 'name'),
        ])
            ->whereNull('exam_id')    // Exclude results with an exam
            ->whereNotNull('assignment_id') // Only results with an assignment
            ->get();

        // Combine the results: exam results first, then assignment results
        $results = $examResults->concat($assignmentResults);

        // Transform the data to a consistent format
        $formattedResults = $results->map(function ($item) {
            $assessment = $item->exam ?? $item->assignment;

            return [
                'id' => $item->id,
                'title' => $assessment->title,
                'studentName' => $item->student->firstname,
                'studentSurname' => $item->student->lastname,
                'teacherName' => $assessment->lesson->teacher->firstname,
                'teacherSurname' => $assessment->lesson->teacher->lastname,
                'score' => $item->score,
                'className' => $assessment->lesson->class->name,
                'startTime' => $item->exam ? $assessment->startTime : $assessment->start_date,
            ];
        });

        return response()->json($formattedResults, 200);
    }

    // fetch all students result related to the guardian
    public function getStudentRelatedByGuardianResults($guardianId)
    {
        $studentIds = Student::where('guardian_id', $guardianId)->pluck('id');
        if ($studentIds->isEmpty()) {
            return response()->json([], 200);
        }
        $examResults = ModelsResult::with([
            'student' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'exam.lesson.teacher' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'exam.lesson.class' => fn($q) => $q->select('id', 'name'),
        ])
            ->whereIn('student_id', $studentIds)
            ->whereNotNull('exam_id')
            ->get();

        // 3. Fetch assignment results for these student IDs
        $assignmentResults = ModelsResult::with([
            'student' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'assignment.lesson.teacher' => fn($q) => $q->select('id', 'firstname', 'lastname'),
            'assignment.lesson.class' => fn($q) => $q->select('id', 'name'),
        ])
            ->whereIn('student_id', $studentIds)
            ->whereNull('exam_id')
            ->whereNotNull('assignment_id')
            ->get();
        $results = $examResults->concat($assignmentResults);
        $formattedResults =  $results->map(function ($item) {
            $assessment = $item->exam ?? $item->assignment;
            return [
                'id' => $item->id,
                'title' => $assessment->title,
                'studentName' => $item->student->firstname,
                'studentSurname' => $item->student->lastname,
                'teacherName' => $assessment->lesson->teacher->firstname,
                'teacherSurname' => $assessment->lesson->teacher->lastname,
                'score' => $item->score,
                'className' => $assessment->lesson->class->name,
                'startTime' => $item->exam ? $assessment->startTime : $assessment->start_date,
            ];
        })->filter()->values();
        return response()->json($formattedResults, 200);
    }

    // CRUD PROCESS FOR THE RESULTS MODELS
    public function createResult(Request $request)
    {
        try {
            $validated = $request->validate([
                'assignment_id' => 'nullable|integer|exists:assignments,id',
                'exam_id' => 'nullable|integer|exists:exams,id',
                'score' => 'required|numeric|between:0,100',
            ]);
            // Optional: Enforce at least one ID 
            if (!$validated['assignment_id'] && !$validated['exam_id']) {
                return response()->json(['error' => 'Result must be linked to an assignment or an exam'], 422);
            }

            $result = new ModelsResult([
                'score' => $request->input('score'),
            ]);
            $result->assignment_id = $request->input('assignment_id');
            $result->exam_id = $request->input('exam_id');
            $result->load('assignment', 'exam');
            $result->save();
            return response()->json($result, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error creating result', 'error' => $th->getMessage()], 500);
        }
    }

    public function updateResult($id, Request $request)
    {
        $result = ModelsResult::find($id);
        if (!$result) {
            return response()->json(['message' => "assignment not found"], 404);
        }
        $validated = $request->validate([
            'assignment_id' => 'nullable|integer|exists:assignments,id',
            'exam_id' => 'nullable|integer|exists:exams,id',
            'score' => 'required|numeric|between:0,100',
        ]);
        // Optional: Enforce at least one ID 
        if (!$validated['assignment_id'] && !$validated['exam_id']) {
            return response()->json(['error' => 'Result must be linked to an assignment or an exam'], 422);
        }
        try {
            $result->update([
                'score' => $request->input('score'),
                'assignment_id' =>  $request->input('assignment_id'),
                'exam_id' => $request->input('exam_id'),
            ]);
            return response()->json(null, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Updating Result failed', 'error' => $th->getMessage()]);
        }
    }

    public function deleteResult($id)
    {
        $result = ModelsResult::find($id);
        if ($result) {
            return response()->json(['message' => 'result not found'], 404);
        }
        $result->delete();
        return response(null, 200);
    }
    public function getResultRelatedData()
    {
        $relatedData = [
            'assignment' => Assignment::select('id', 'title')->get(),
            'exam' => Exam::select('id', 'title')->get(),
        ];
        return response()->json($relatedData, 200);
    }
}
