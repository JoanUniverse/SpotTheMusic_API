<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{
    //List all users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    //Gets all the info form one user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    //Deletes one user
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['status' => $id . ' Deleted successfully'], 200);
    }

    //Creates one user and Ecryps password
    public function store(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'username' => 'required|unique:users,username',
                'password' => 'required',
                'email' => 'required|unique:users,email'
            ]
        );

        if(!$validation->fails()){
            $user = new User();

            $password = Hash::make($request->password);
    
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $password;
            if ($user->save()) {
                return response()->json(['status' => 'Created', 'result' => $user]);
            } else {
                return response()->json(['status' => 'Error while creating']);
            }
        } else {
            return response()->json($validation->getMessageBag());
        }

    }

    //Modify one user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->update($request->all())) {
            return response()->json(['status' => 'Modified successfully', 'result' => $user]);
        } else {
            return response()->json(['status' => 'Error while modifying']);
        }
    }

    //Uploads a picture for one user
    public function picture(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'picture' => 'required|mimes:jpeg,jpg,bmp,png|max:10240',
        ]);
        $user = User::findOrFail($id);
        if (!$validation->fails()) {
            $filename = "user$id" . "_" . time() . "." . $request->picture->extension();
            $request->picture->move(public_path('user_images'), $filename);
            $urifoto = url('user_images') . '/' . $filename;
            $user->picture = $urifoto;
            $user->save();
            return response()->json(['status' => 'Image uploaded successfully', 'uri' => $urifoto], 200);
        } else {
            return response()->json(['status' => 'Error: wrong type or size'], 404);
        }
    }

    //Modifies the location of a user
    public function location(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->location = $request->location;
        if($user->save()){
            return response()->json(['status' => 1, 'result' => $user]);
        } else {
            return response()->json(['status' => 0, 'result' => 'Could not update user location']);
        }
    }

    //Gets all users that are nearby
    public function nearUsers($id)
    {
        $range = 0.5;

        $user = User::findOrFail($id);
        $location = $user->location;
        if (!$user->allowLocation) return response()->json(['status' => 'This user has not allowed location term'], 403);
        if (!$location) return response()->json(['status' => 'This user has no location'], 404);
        $parts = explode(",", $location);
        $minLatitude = floatval($parts[0]) - $range;
        $maxLatitude = floatval($parts[0]) + $range;
        $minLongitude = floatval($parts[1]) - $range;
        $maxLongitude = floatval($parts[1]) + $range;

        $users = User::where("location", "!=", null)->where("id_user", "!=", $id)->get();
        $result = new Collection();
        foreach ($users as $u) {
            $locParts = explode(",", $u->location);
            if ((floatval($locParts[0]) > $minLatitude && floatval($locParts[0]) < $maxLatitude) && (floatval($locParts[1]) > $minLongitude && floatval($locParts[1]) < $maxLongitude)){
                $result->add($u);
            }
        }

        if(!count($result)) return response()->json(['status' => 0, 'message' => 'No users nearby'], 404);
        return response()->json(['status' => 1, 'result' => $result]);
    }
}