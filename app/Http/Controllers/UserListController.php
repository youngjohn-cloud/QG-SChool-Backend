<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    // CRUD PROCESS FOR PARENTS
    public function userList()
    {
        $user = User::with('students')->get();
        return response()->json($user, 200);
    }
    public function createParent(ParentRequest $request)
    {
        try {
            //code...
            $parent = User::create([
                'firstname' => $request->input("firstname"),
                'middlename' => $request->input("middlename"),
                'lastname' => $request->input("lastname"),
                'email' => $request->input("email"),
                'password' => $request->input("password"),
                'phone' => $request->input("phone"),
                'address' => $request->input("address"),
                'gender' => $request->input("sex"),
            ]);
            return response()->json([$parent, 200]);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
                "message" => "Error creating parent"
            ], 500);
        }
    }
    public function updateParent($id, ParentRequest $request)
    {
        $parent = User::find($id);
        if (!$parent) {
            return response()->json(["error" => "Parent not found"], 404);
        }
        $parent->update([
            'firstname' => $request->input("firstname"),
            'middlename' => $request->input("middlename"),
            'lastname' => $request->input("lastname"),
            'email' => $request->input("email"),
            'password' => $request->input("password"),
            'phone' => $request->input("phone"),
            'address' => $request->input("address"),
            'gender' => $request->input("sex"),
        ]);
        return response()->json($parent, 200);
    }
    public function deleteParent($id)
    {
        $parent = User::find($id);
        if (!$parent) {
            return response()->json(["error" => "Parent not found"], 404);
        }
        $parent->delete();
        return response()->json(["message" => "Parent deleted Succesfully"], 204);
    }
}
