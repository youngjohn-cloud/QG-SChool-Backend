<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // method for the exam list and eager load the lesson class
    public function examList()
    {
        $exam = Exam::with('lesson.subject', 'lesson.class', 'lesson.teacher')->get();
        return response()->json($exam, 200);
    }
}
