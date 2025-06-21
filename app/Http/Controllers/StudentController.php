<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Level;
use App\Models\Qg_Class;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // CRUD METHOD FOR THE STUDENT MODELS

    // ALL STUDENTS FOR THE ADMIN TO SEE
    public function studentList()
    {
        $students = Student::with('level', 'class', 'guardian')->get();
        return response()->json($students, 200);
    }

    // ALL STUDENTS OF THE PARENT OR GUARDIAN
    public function getStudentsByGuardian($guardianId)
    {
        $wards = Student::with(['level', 'class', 'guardian'])->where('guardian_id', $guardianId)->get();
        return response()->json($wards, 200);
    }

    public function createStudent(StudentRequest $request)
    {
        try {
            $student = new Student();
            $student->firstname = $request->input('firstname');
            $student->lastname = $request->input('lastname');
            $student->email = $request->input('email');
            $student->password = $request->input('password');
            $student->phone = $request->input('phone');
            $student->gender = $request->input('sex');
            $student->home_address = $request->input('address');
            $student->dob = $request->date('dob');
            $student->blood_type = $request->input('bloodType');
            if ($request->hasFile('image')) {
                $student->student_dp = $request->file('image')->store('QG-SCHOOLS');
            } else {
                $student->student_dp = null;
            }
            $student->guardian_id = $request->input('parentId');
            $student->level_id = $request->input('levelId');
            $student->class_id = $request->input('classId');
            $student->save();
            return response()->json($student, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error creating student', 'error' => $th->getMessage()], 500);
        }
    }

    // update a single teacher
    public function updateStudent($id, StudentRequest $request)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }
        // update all fields
        $student->update([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'sex' => $request->input('sex'),
            'dob' => $request->date('dob'),
            'address' => $request->input('address'),
            'blood_type' => $request->input('bloodType'),
            'guardian_id' => $request->input('parentId'),
            'level_id' => $request->input('levelId'),
            'class_id' => $request->input('classId'),
        ]);
        return response()->json($student, 200);
    }
    public function getStudentRelatedData()
    {
        $grade = Level::select('id', 'name')->get();
        $studentClass = Qg_Class::withCount('students')->get();
        $relatedData = [
            'classes' => $studentClass,
            'grades' => $grade
        ];
        return response()->json($relatedData, 200);
    }

    // Show a single student
    public function showStudent($id)
    {
        $student = Student::with(['class'])->find($id);
        if (!$student) {
            return response()->json(['message' => 'student not found'], 404);
        }

        return response()->json($student);
    }
    // Delete a student
    public function deleteStudent($id)
    {
        $student = Student::find($id);
        if (!$student) return response()->json(['message' => 'student not found'], 404);
        $student->delete();
        return response()->json(['message' => 'Teacher deleted successfully'], 200);
    }
}
