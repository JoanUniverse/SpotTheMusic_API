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

    //List of songs by name
    public function indexName($name)
    {
        $songs = Song::where('name', 'like', '%' . $name . '%')
                        ->get();
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

        if($request->song_picture){
            $validation = Validator::make($request->all(), [
                'link' => 'required|mimes:mpga,wav,mp3,mpeg,mp4|max:10240',
                'song_picture' => 'required|mimes:jpeg,jpg,bmp,png|max:10240',
            ]);
        } else{
            $validation = Validator::make($request->all(), [
                'link' => 'required|mimes:mpga,wav,mp3,mpeg,mp4|max:10240',
            ]);
        }


        if (!$validation->fails()) {
            $filenameSong = "song$request->name" . "_" . time() . "." . $request->link->extension();
            $request->link->move(public_path('artist_songs'), $filenameSong);
            $urisong = url('artist_songs') . '/' . $filenameSong;

            if($request->song_picture){
                $filenamePic = "img$request->name" . "_" . time() . "." . $request->song_picture->extension();
                $request->song_picture->move(public_path('song_images'), $filenamePic);
                $uripic = url('song_images') . '/' . $filenamePic;
                $song->song_picture = $uripic;
            }


            $song->link = $urisong;
            $song->save();
            return response()->json(['status' => 'Song uploaded successfully', 'uri' => $urisong], 200);
        } else {
            return response()->json(['status' => "File extension -> " . $request->link->extension(), "message" => $validation->getMessageBag()], 404);
        }
    }
}
