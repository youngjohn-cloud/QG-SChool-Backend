<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Qg_Class;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // assignment list
    public function announcementList()
    {
        $announcement = Announcement::with('class')->get();
        return response()->json($announcement, 200);
    }

    // CRUD PROCESS FOR THE EVENT MODELS
    public function createAnnouncement(Request $request)
    {
        try {
            $announcement = new Announcement([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'date' => $request->input('date'),
            ]);
            $announcement->class_id = $request->input('class_id');
            $announcement->load('class');
            $announcement->save();
            return response()->json($announcement, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error creating Announcement', 'error' => $th->getMessage()], 500);
        }
    }
    public function updateAnnouncement($id, Request $request)
    {
        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response()->json(['message' => "Announcement not found"], 404);
        }
        try {
            $announcement->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'date' => $request->input('date'),
                'class_id' => $request->input('class_id'),
            ]);
            return response()->json(null, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Updating Announcement failed', 'error' => $th->getMessage()]);
        }
    }
    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }
        $announcement->delete();
        return response(null, 200);
    }
    public function getEventRelatedData()
    {
        $relatedData = Qg_Class::select('id', 'name')->get();
        return response()->json($relatedData, 200);
    }
}
