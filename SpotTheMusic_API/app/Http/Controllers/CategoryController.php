<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
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
            return response()->json(['status' => 'Error guardant']);
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
}
