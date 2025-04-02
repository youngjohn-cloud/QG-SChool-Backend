<?php

namespace App\Http\Controllers;

use App\Models\Qg_Class;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QgClassController extends Controller
{
    // get the class list
    public function classList()
    {
        $class = Qg_Class::with([
            'level' => fn($query) => $query->select('id', 'name'),
            'teachers' => fn($query) => $query->select('id', 'firstname'),
        ])->get();
        return response()->json($class, 200);
    }

    // create a new Class and pass the teacher and level
    public function classRegister(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'name' => 'required',
            'capacity' => 'required',
            'levelId' => 'required',
        ]);
        if ($Validator->fails()) {
            return response()->json($Validator->errors(), 400);
        }
        try {
            $class = new Qg_Class();
            $class->name = $request->input('name');
            $class->capacity = $request->input('capacity');
            $class->level_id = $request->input('levelId');
            $class->teacher_id = $request->input('teacherId');
            $class->save();
            return response()->json($class->load('teachers', 'level'), 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Error creating subject', 'message' => $th->getMessage()], 500);
        }
    }
    // upadate a class and also the level_id and teacher_id
    public function updateClass($id, Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'name' => 'required',
            'capacity' => 'required',
            'levelId' => 'required',
        ]);
        if ($Validator->fails()) {
            return response()->json($Validator->errors(), 400);
        }
        try {
            $class =  Qg_Class::find($id);
            if (!$class) {
                return response()->json(['errors' => "Class not found"], 404);
            }
            $class->update([
                'name' => $request->input('name'),
                'capacity' => $request->input('capacity'),
                'level_id' => $request->input('levelId'),
                'teacher_id' => $request->input('teacherId'),
            ]);
            return response()->json($class->load('teachers', 'level'), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error updating class',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    // Delete a class
    public function classDelete($id)
    {
        $class = Qg_Class::find($id);
        if (!$class) {
            return response()->json(['error' => 'Subject not found'], 404);
        }
        $class->delete();
        return response()->json(null, 204);
    }
}
