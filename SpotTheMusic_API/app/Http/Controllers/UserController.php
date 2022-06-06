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

    //Modifies the location of a user
    public function listeningNow(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->listening_now = $request->listening_now;
        if($user->save()){
            return response()->json(['status' => 1, 'result' => $user]);
        } else {
            return response()->json(['status' => 0, 'result' => 'Could not update user music']);
        }
    }

    //Gets all users that are nearby
    public function nearUsers(Request $request, $id)
    {
        $value = $request->distance;
        if($value > 2 || $value < 0) return response()->json(['status' => 'Range value must be -> 0, 1 or 2'], 403);

        $range = array(0.2, 0.5, 1.0);

        $user = User::findOrFail($id);
        $location = $user->location;
        if (!$user->allowLocation) return response()->json(['status' => 'This user has not allowed location term'], 403);
        if (!$location) return response()->json(['status' => 'This user has no location'], 404);
        $parts = explode(",", $location);
        $minLatitude = floatval($parts[0]) - $range[$value];
        $maxLatitude = floatval($parts[0]) + $range[$value];
        $minLongitude = floatval($parts[1]) - $range[$value];
        $maxLongitude = floatval($parts[1]) + $range[$value];

        $usersWithCategories = array();
        if($request->categories) $usersWithCategories = (new CategoryController)->usersWithCategoryIds($request->categories);

        $users = User::where("location", "!=", null)->where("allowLocation", "!=", 0)->where("id_user", "!=", $id)->get();
        $result = new Collection();
        foreach ($users as $u) {
            $locParts = explode(",", $u->location);
            if ((floatval($locParts[0]) > $minLatitude && floatval($locParts[0]) < $maxLatitude) && (floatval($locParts[1]) > $minLongitude && floatval($locParts[1]) < $maxLongitude)){
                if(!$request->categories){
                    $result->add($u);
                }else{
                    if($usersWithCategories->contains($u->id_user)) $result->add($u);
                }               
            }
        }

        if(!count($result)) return response()->json(['status' => 0, 'message' => 'No users nearby'], 404);
        return response()->json(['status' => 1, 'result' => $result]);
    }
}