<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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

    //Lists all user that you have messages with and the las message
    public function indexList($idUser)
    {
        $results = DB::select("SELECT * FROM messages, (SELECT MAX(id_message) as lastid FROM messages WHERE (messages.userTo = $idUser OR messages.userFrom = $idUser) GROUP BY CONCAT(LEAST(messages.userTo, messages.userFrom),'.', GREATEST(messages.userTo, messages.userFrom)) ) as conversations WHERE id_message = conversations.lastid ORDER BY messages.date DESC;");
        $resultList = new Collection();
        foreach($results as $result) {
            $userInfo = $result->userFrom;
            if($result->userTo != $idUser) $userInfo = $result->userTo;
            $user = User::findOrFail($userInfo);
            $message = new Message();
            $message->id_message = $result->id_message;
            $message->text = $result->text;
            $message->date = $result->date;
            $resultList = $resultList->add(array($user, $message));
        }
        return response()->json($resultList, 200);
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
