<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // count all guardian,teacher,student
    public function countUsers(Request $request)
    {
        $request->validate([
            'type' => 'required|string|min:1'
        ]);
        $modelMap = [
            'guardian' => User::class,
            'teacher' => Teacher::class,
            'student' => Student::class,
        ];

        $data = $modelMap[$request->input('type')]::count();
        return response()->json($data, 200);
    }
    public function getGenderCounts()
    {
        $counts = Student::select(
            DB::raw('SUM(CASE WHEN gender = "male" THEN 1 ELSE 0 END) AS boys'),
            DB::raw('SUM(CASE WHEN gender = "female" THEN 1 ELSE 0 END) AS girls')
        )->first(); // Get the single result row

        $boys = (int)($counts->boys ?? 0);
        $girls = (int)($counts->girls ?? 0);

        return response()->json([
            'boys' => $boys,
            'girls' => $girls,
        ], 200);
    }
}
