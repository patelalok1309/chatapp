<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleteEvent;
use App\Events\MessageEvent;
use App\Models\Chat;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loadDashboard()
    {
        $users = User::whereNotIn('id', [auth()->user()->id])->get();
        return view('dashboard', compact('users'));
    }

    public function saveChat(Request $request)
    {
        try {

            $chat = Chat::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message
            ]);

            event (new MessageEvent($chat));

            return response()->json(['success' => true, 'data' => $chat]);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            
        }
    }

    public function loadChats(Request $request)
    {
        try {

            $chats = Chat::where(function($query) use ($request){
                $query->where('sender_id' , '=' , $request->sender_id)
                    ->orWhere('sender_id' , '=' , $request->receiver_id);
            })->where(function($query) use ($request){
                $query->where('receiver_id' , '=' , $request->receiver_id)
                    ->orWhere('receiver_id' , '=' , $request->sender_id);
            })->get();

            return response()->json(['success' => true, 'data' => $chats]);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            
        }
    }

    public function getUser(Request $request){

        $user = User::find($request->receiver_id);

        if($user){
            return response()->json(['success' => true , 'data' => $user]);
        }
        return response()->json(['success' => false , 'msg' => 'User does not exists']);
    }

    public function deleteChat(Request $request){
        try {

            Chat::where('id' , $request->id)->delete();

            event (new MessageDeleteEvent($request->id));

            return response()->json(['success' => true, 'msg' => 'Message deleted successfully']);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            
        }
    }
}
