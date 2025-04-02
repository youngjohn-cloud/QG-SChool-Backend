<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // assignment list
    public function announcementList()
    {
        $announcement = Announcement::with('class')->get();
        return response()->json($announcement, 200);
    }
}
