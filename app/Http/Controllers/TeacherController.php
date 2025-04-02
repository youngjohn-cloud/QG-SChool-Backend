<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    // CRUD methods for the teacher
    public function teachersList()
    {
        $teachers = Teacher::with('classes', 'subjects')->get();
        return response()->json($teachers, 200);
    }
    // get Teachers information
    public function getTeachers()
    {
        $teachers = Teacher::select('id', 'teacher_id', 'firstname', 'lastname')->get();
        return response()->json($teachers, 200);
    }

    public function createTeacher(TeacherRequest $request)
    {
        try {
            $teacher = new Teacher();
            $teacher->teacher_id = $request->input('id');
            $teacher->firstname = $request->input('firstname');
            $teacher->lastname = $request->input('lastname');
            $teacher->email = $request->input('email');
            $teacher->password = $request->input('password');
            $teacher->phone = $request->input('phone');
            $teacher->gender = $request->input('sex');
            $teacher->address = $request->input('address');
            $teacher->dob = $request->date('dob');
            $teacher->qualifications = $request->input('qualifications');
            $teacher->blood_type = $request->input('bloodType');
            $teacher->hire_date = $request->input('hire_date');
            if ($request->hasFile('image')) {
                $teacher->teacher_dp = $request->file('image')->store('QG-SCHOOLS');
            } else {
                $teacher->teacher_dp = null;
            }
            $teacher->save();
            // Attach subjects to the teachers if provided in the request.
            if ($request->has('subjects')) {
                $teacher->subjects()->sync($request->input('subjects'));
            }
            // Load teachers in the response
            $teacher->load('subjects');
            return response()->json($teacher, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error creating Teacher', 'message' => $th->getMessage()], 500);
        }
    }
    // update a single teacher
    public function updateTeacher($id, TeacherRequest $request)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }
        // update all fields
        $teacher->update([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'sex' => $request->input('sex'),
            'dob' => $request->date('dob'),
            'address' => $request->input('address'),
        ]);
        if ($request->has('subjects')) {
            $teacher->subjects()->sync($request->input('subjects'));
        } elseif ($request->exists('subjects')) {
            // If teachers is explicitly empty, remove all relationships
            $teacher->subjects()->detach();
        }
        // Note: If 'teachers' isn't in the request at all, we leave existing relationships unchanged

        // Load teachers in the response
        $teacher->load('subjects');
        return response()->json($teacher, 200);
    }
    // Show a single teacher
    public function showTeacher($id)
    {
        $teacher = Teacher::withCount(['classes', 'lessons', 'subjects'])->find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        return response()->json($teacher);
    }
    // method to delete a Teacher 
    public function deleteTeacher($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['error' => 'teacher not Found'], 400);
        }
        $teacher->delete();
        return response()->json(['message' => 'Teacher deleted successfully'], 200);
    }
}
