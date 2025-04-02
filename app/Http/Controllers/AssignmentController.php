<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    // assignment list 
    public function assignmentList()
    {
        $assignments = Assignment::with('lesson.subject', 'lesson.class', 'lesson.teacher')->get();
        return response()->json($assignments, 200);
    }
}
