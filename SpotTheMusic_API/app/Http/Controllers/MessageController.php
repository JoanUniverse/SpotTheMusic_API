<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //Return all messages between 2 users
    public function index($idFrom, $idTo)
    {
        $messages = Message::where("userFrom", "=", $idFrom)
                            ->where("userTo", "=", $idTo)
                            ->orWhere("userFrom", "=", $idTo)
                            ->where("userTo", "=", $idFrom)->get();
        return response()->json($messages);
    }

    // public function show($id)
    // {
    //     $message = Message::findOrFail($id);
    //     return response()->json($message);
    // }

    //Deletes a message
    public function delete($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();
        return response()->json(['status' => $id . ' Deleted successfully'], 200);
    }

    //Saves a message
    public function store(Request $request)
    {
        $message = new Message();
        $message->userFrom = $request->userFrom;
        $message->userTo = $request->userTo;
        $message->text = $request->text;
        if ($message->save()) {
            return response()->json(['status' => 'Created', 'result' => $message]);
        } else {
            return response()->json(['status' => 'Error guardant']);
        }
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        if ($message->update($request->all())) {
            return response()->json(['status' => 'Modified successfully', 'result' => $message]);
        } else {
            return response()->json(['status' => 'Error while modifying']);
        }
    }
}
