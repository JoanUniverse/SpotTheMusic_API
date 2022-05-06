<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Follower;
use App\Http\Controllers\FollowerController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class PostController extends Controller
{
    //Return all the posts
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    //Returns all the posts from one user
    public function show($id)
    {
        $posts = Post::where("user", "=", $id)->get();
        return response()->json($posts);
    }

    //Returns all the posts from one user (Objects)
    public function showObject($id)
    {
        $posts = Post::where("user", "=", $id)->get();
        return $posts;
    }

    //Return all the posts from the users I follow
    public function showFollowed($id)
    {
        $followers = (new FollowerController)->showFollowsObject($id);
        $posts = new Collection();
        if(count($followers) === 0) return response()->json(['status' => 0, 'message' => 'You have no followers']);
        foreach ($followers as $follower) {
            $userPosts = $this->showObject($follower->userFollowed);
            if(count($userPosts) != 0){
                $posts = $posts->merge($userPosts);
            }
        }
        if(count($posts) === 0) return response()->json(['status' => 0, 'message' => 'No posts found :(']);
        return response()->json($posts);
    }

    //Deletes one post
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['status' => $id . ' Deleted successfully'], 200);
    }

    //Saves one post
    public function store(Request $request)
    {
        $post = new Post();
        $post->user = $request->user;
        $post->text = $request->text;
        if ($post->save()) {
            return response()->json(['status' => 'Created', 'result' => $post]);
        } else {
            return response()->json(['status' => 'Error while saving']);
        }
    }

    public function update(Post $request, $id)
    {
        $post = Follower::findOrFail($id);
        if ($post->update($request->all())) {
            return response()->json(['status' => 'Modified successfully', 'result' => $post]);
        } else {
            return response()->json(['status' => 'Error while modifying']);
        }
    }
}
