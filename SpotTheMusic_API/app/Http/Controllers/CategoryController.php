<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    //All categories that 1 user follows
    public function show($id)
    {
        $category = Category::join('user_category', 'user_category.id_category', '=', 'categories.id_category')
                            ->where('user_category.id_user', '=', $id)
                            ->get();
        if(!count($category)) return response()->json(['status' => 0, 'result' => 'This user has no categories']);
        return response()->json(['status' => 1, 'result' => $category]);
    }

    //Users that have categories in common
    public function showCommon($idUser)
    {
        $categories = Category::select('user_category.id_category')
                            ->join('user_category', 'user_category.id_category', '=', 'categories.id_category')
                            ->where('user_category.id_user', '=', $idUser)
                            ->get();
        if(!count($categories)) return response()->json(['status' => 0, 'result' => 'This user has no categories']);

        $users = User::join('user_category', 'user_category.id_user', '=', 'users.id_user')
                            ->where('user_category.id_user', '!=', $idUser)
                            ->whereIn('user_category.id_category', $categories)
                            ->get();
        if(!count($users)) return response()->json(['status' => 0, 'result' => 'No users found']);
        return response()->json(['status' => 1, 'result' => $users->unique()]);
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['status' => $id . ' Deleted successfully'], 200);
    }

    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        if ($category->save()) {
            return response()->json(['status' => 'Created', 'result' => $category]);
        } else {
            return response()->json(['status' => 'Error while saving']);
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($category->update($request->all())) {
            return response()->json(['status' => 'Modified successfully', 'result' => $category]);
        } else {
            return response()->json(['status' => 'Error while modifying']);
        }
    }

    //Add/Remove a UserCategory
    public function AddRemove($idUser, $idCategory)
    {
        $category = DB::select("SELECT * FROM user_category where id_user = $idUser AND id_category = $idCategory");
        if(!$category){
            DB::insert('insert into user_category (id_user, id_category) values (?, ?)', [$idUser, $idCategory]);
            return response()->json(['status' => 1, 'message' => 'Category added']);
        }
        DB::delete("delete from user_category where id_user = $idUser and id_category = $idCategory");
        return response()->json(['status' => 1, 'message' => 'Category removed']);
    }
}
