<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['status' => $id . ' Deleted successfully'], 200);
    }

    public function store(Request $request)
    {
        $user = new User();

        $password = Hash::make($request->password);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $password;
        if ($user->save()) {
            return response()->json(['status' => 'Created', 'result' => $user]);
        } else {
            return response()->json(['status' => 'Error guardant']);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->update($request->all())) {
            return response()->json(['status' => 'Modified successfully', 'result' => $user]);
        } else {
            return response()->json(['status' => 'Error while modifying']);
        }
    }
}
