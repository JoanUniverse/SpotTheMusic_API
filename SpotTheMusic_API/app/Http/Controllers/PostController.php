<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Follower;
use App\Http\Controllers\FollowerController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class PostController extends Controller
{
    //Return all the posts
    public function index()
    {
        $posts = Post::orderBy('date', 'DESC')->get();
        return response()->json($posts);
    }

    //Returns all the posts from one user
    public function show($id)
    {
        $posts = Post::where("user", "=", $id)->get();
        $reposts = Post::join('post_reposts', 'post_reposts.post_id', '=', 'posts.id_post')
                    ->where('post_reposts.user_id', '=', $id)->get();
        return response()->json($posts->merge($reposts)->sortByDesc('date')->values());
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
        $posts = $posts->merge($this->showObject($id));
        //if(count($followers) === 0) return response()->json(['status' => 0, 'message' => 'You have no followers']);
        foreach ($followers as $follower) {
            $userPosts = $this->showObject($follower->userFollowed);
            if(count($userPosts) != 0){
                $posts = $posts->merge($userPosts);
            }
        }
        if(count($posts) === 0) return response()->json(['status' => 0, 'message' => 'No posts found :(']);
        return response()->json($posts->sortByDesc('date')->values());
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

    //Likes/Dislikes a Post
    public function likeDislike($idPost, $idUser)
    {
        $like = DB::select("SELECT * FROM post_likes where post_id = $idPost AND user_id = $idUser");
        if(!$like){
            DB::insert('insert into post_likes (post_id, user_id) values (?, ?)', [$idPost, $idUser]);
            $post = Post::findOrFail($idPost);
            return response()->json(['status' => 1, 'message' => 'Post liked', 'post' => $post]);
        }
        DB::delete("delete from post_likes where post_id = $idPost and user_id = $idUser");
        $post = Post::findOrFail($idPost);
        return response()->json(['status' => 1, 'message' => 'Post disliked :(', 'post' => $post]);
    }

    //Spot a Post
    public function spotPost($idPost, $idUser)
    {
        $like = DB::select("SELECT * FROM post_reposts where post_id = $idPost AND user_id = $idUser");
        if(!$like){
            DB::insert('insert into post_reposts (post_id, user_id) values (?, ?)', [$idPost, $idUser]);
            $post = Post::findOrFail($idPost);
            return response()->json(['status' => 1, 'message' => 'Post spotted', 'post' => $post]);
        }
        DB::delete("delete from post_reposts where post_id = $idPost and user_id = $idUser");
        $post = Post::findOrFail($idPost);
        return response()->json(['status' => 1, 'message' => 'Post no longer spotted', 'post' => $post]);
    }
}
