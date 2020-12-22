<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Events\Messages;

class ChatController extends Controller
{

    public function sendMessage(Request $request){
        //getting the user id
        $user_id = $request->input('user_id');     
        $user = User::findOrFail($user_id);

        $message = new Message();
        $message->sender_id = $user_id;
        $message->room_id = $request->input('room_id');
        $message->message = $request->input('message');
        $message->save();

        broadcast(new Messages($message,$user))->toOthers();
    }
}
