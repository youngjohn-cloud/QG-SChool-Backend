<?php

namespace App\Http\Controllers;

use App\Models\Result as ModelsResult;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    // fetch all results
    public function resultList()
    {
        $result = ModelsResult::with('student', 'exam', 'exam.lesson.teacher', 'exam.lesson.class')->get();
        return response()->json($result, 200);
    }
}
