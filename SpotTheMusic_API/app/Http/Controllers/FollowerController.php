<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
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
        $followers = User::join('followers', 'followers.userFollows', '=', 'users.id_user')
                            ->where("followers.userFollowed", "=", $id)->get();
        return response()->json($followers);
    }

    //Return all users that 1 user follows
    public function showFollows($id)
    {
        $followers = User::join('followers', 'followers.userFollowed', '=', 'users.id_user')
                ->where("userFollows", "=", $id)->get();
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
        $followers = User::whereNotIn('id_user', Follower::where("userFollows", "=", $id)->select('userFollowed'))
                            ->where("id_user", "!=", $id)->get();
        return response()->json($followers);
    }

    //Unfollows
    public function delete($idFrom, $idTo)
    {
        $follower = Follower::where("userFollows", "=", $idFrom)->where("userFollowed", "=", $idTo);
        if($follower->delete()) return response()->json(['status' => 1, 'result' => 'Unfollowed successfully'], 200);
        return response()->json(['status' => 0, 'result' => 'Not found'], 404);
    }

    public function store(Request $request)
    {
        $follower = new Follower();
        $follower->userFollows = $request->userFollows;
        $follower->userFollowed = $request->userFollowed;
        if ($follower->save()) {
            return response()->json(['status' => 1, 'result' => $follower]);
        } else {
            return response()->json(['status' => 0, 'result' => 'Error while saving']);
        }
    }
}
