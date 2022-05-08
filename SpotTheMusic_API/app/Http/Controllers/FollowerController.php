<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function index()
    {
        $followers = Follower::all();
        return response()->json($followers);
    }

    //Return all followers from 1 user
    public function show($id)
    {
        $followers = Follower::where("userFollowed", "=", $id)->get();
        return response()->json($followers);
    }

    //Return all users that 1 user follows
    public function showFollows($id)
    {
        $followers = Follower::where("userFollows", "=", $id)->get();
        return response()->json($followers);
    }

    //Return all users that 1 user follows (Objects)
    public function showFollowsObject($id)
    {
        $followers = Follower::where("userFollows", "=", $id)->get();
        return $followers;
    }

    //Return users that I don't follow
    public function showNewFollowers($id){
        $followers = Follower::where("userFollows", "!=", $id)->where("userFollowed", "!=", $id)->get();
        return response()->json($followers);
    }

    public function delete($id)
    {
        $follower = Follower::findOrFail($id);
        $follower->delete();
        return response()->json(['status' => $id . ' Deleted successfully'], 200);
    }

    public function store(Request $request)
    {
        $follower = new Follower();
        $follower->userFollows = $request->userFollows;
        $follower->userFollowed = $request->userFollowed;
        if ($follower->save()) {
            return response()->json(['status' => 'Created', 'result' => $follower]);
        } else {
            return response()->json(['status' => 'Error while saving']);
        }
    }

    public function update(Request $request, $id)
    {
        $follower = Follower::findOrFail($id);
        if ($follower->update($request->all())) {
            return response()->json(['status' => 'Modified successfully', 'result' => $follower]);
        } else {
            return response()->json(['status' => 'Error while modifying']);
        }
    }
}
