<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SongController extends Controller
{
    //List all songs
    public function index()
    {
        $songs = Song::all();
        return response()->json($songs);
    }

    //Gets all the info form one song
    public function show($id)
    {
        $song = Song::findOrFail($id);
        return response()->json($song);
    }

    public function store(Request $request)
    {
        $song = new Song();
        $song->name = $request->name;
        $song->category = $request->category;
        $song->artist = $request->artist;

        $validation = Validator::make($request->all(), [
            'link' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac|max:10240',
        ]);

        if (!$validation->fails()) {
            $filename = "song$request->name" . "_" . time() . "." . $request->link->extension();
            $request->link->move(public_path('artist_songs'), $filename);
            $urisong = url('artist_songs') . '/' . $filename;
            $song->link = $urisong;
            $song->save();
            return response()->json(['status' => 'Song uploaded successfully', 'uri' => $urisong], 200);
        } else {
            return response()->json(['status' => 'Error saving song'], 404);
        }
    }
}
