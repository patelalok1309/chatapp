<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleteEvent;
use App\Events\MessageEvent;
use App\Events\MessageUpdateEvent;
use App\Models\Chat;
use App\Models\Group;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Load Dashboard Users
    public function loadDashboard()
    {
        $users = User::whereNotIn('id', [auth()->user()->id])->orderBy('created_at', 'DESC')->get();
        return view('dashboard', compact('users'));
    }


    // Save Chat - new messages 
    public function saveChat(Request $request)
    {
        try {

            $chat = Chat::create([
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message
            ]);

            event(new MessageEvent($chat));

            return response()->json(['success' => true, 'data' => $chat]);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    // Load Older chats 
    public function loadChats(Request $request)
    {
        try {

            $chats = Chat::where(function ($query) use ($request) {
                $query->where('sender_id', '=', $request->sender_id)
                    ->orWhere('sender_id', '=', $request->receiver_id);
            })->where(function ($query) use ($request) {
                $query->where('receiver_id', '=', $request->receiver_id)
                    ->orWhere('receiver_id', '=', $request->sender_id);
            })->get();

            return response()->json(['success' => true, 'data' => $chats]);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    // Load Group Chat
    public function loadGroups(Request $request)
    {
        $groups = Group::where('user_id', auth()->user()->id)->get();
        return view('groups' , compact('groups'));
    }


    // Get Details for single user 
    public function getUser(Request $request)
    {

        $user = User::find($request->receiver_id);

        if ($user) {
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'msg' => 'User does not exists']);
    }


    // Delete Chat 
    public function deleteChat(Request $request)
    {

        try {

            Chat::where('id', $request->id)->delete();

            event(new MessageDeleteEvent($request->id));

            return response()->json(['success' => true, 'msg' => 'Message deleted successfully']);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    // Update chats 
    public function updateChat(Request $request)
    {
        try {

            Chat::where('id', $request->id)->update([
                'message' => $request->message,
            ]);

            event(new MessageUpdateEvent($request->id, $request->message));

            return response()->json(['success' => true, 'msg' => 'Message Updated Successfully']);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' =>  $e->getMessage()]);
        }
    }


    // Create Groups
    public function createGroup(Request $request)
    {
        try {
            $imageName = '';

            if ($request->image) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $imageName = 'images/' . $imageName;
            }

            Group::insert([
                'user_id' => auth()->user()->id,
                'name' => $request->name,
                'join_limit' => $request->join_limit,
                'image' => $imageName
            ]);

            return response()->json(['success' => false, 'msg' =>  $request->name . ' Group has been created successfully']);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'msg' =>  $e->getMessage()]);
        }
    }
}
