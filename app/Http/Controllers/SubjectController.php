<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function subjectList()
    {
        // Fetch all the data from the subject model
        $subjectList = Subject::with('teacher')->get();
        // return response in a json format
        return response()->json($subjectList, 200);
    }
    // create a new Subject
    public function subjectRegister(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'name' => 'required',
            'level' => 'required',
            'teachers' => 'array',              // Optional array of teacher IDs
            'teachers.*' => 'exists:teachers,id' // Each ID must exist in teachers table
        ]);
        if ($Validator->fails()) {
            return response()->json($Validator->errors(), 400);
        }
        try {
            $subject = new Subject();
            $subject->name = $request->input('name');
            $subject->level = $request->input('level');
            $subject->save();
            // Attach teachers to the subject if provided in the request.
            if ($request->has('teacher')) {
                $subject->teacher()->sync($request->input('teacher'));
            }
            // Load teachers in the response
            $subject->load('teacher');

            return response()->json($subject, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Error creating subject', 'message' => $th->getMessage()], 500);
        }
    }
    public function subjectUpdate($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'level' => 'required|string',
                'teacher' => 'array',              // Optional array of teacher IDs
                'teacher.*' => 'exists:teachers,id' // Each ID must exist in teachers table
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $subject = Subject::find($id);
            if (!$subject) {
                return response()->json(['error' => 'Subject not found'], 404);
            }

            // Update basic fields
            $subject->update([
                'name' => $request->input('name'),
                'level' => $request->input('level'),
            ]);
            if ($request->has('teacher')) {
                $subject->teacher()->sync($request->input('teacher'));
            } elseif ($request->exists('teacher')) {
                // If teachers is explicitly empty, remove all relationships
                $subject->teacher()->detach();
            }
            // Note: If 'teachers' isn't in the request at all, we leave existing relationships unchanged

            // Load teachers in the response
            $subject->load('teacher');

            return response()->json($subject, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error updating subject',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function subjectDelete($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }
        $subject->delete();
        return response()->json(null, 204);
    }
    // get Teachers information
    public function getSubjects()
    {
        $subject = Subject::select('id', 'name')->get();
        return response()->json($subject, 200);
    }
}
