<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
