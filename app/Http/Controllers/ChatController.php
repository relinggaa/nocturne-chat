<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function showUsernameForm()
    {
        return view('username');
    }

    public function setUsername(Request $request)
    {
        $request->validate(['username' => 'required|max:50']);
        $request->session()->put('username', $request->username);

        return redirect()->route('chat');
    }

    public function showChat()
    {
        return view('chat');
    }

    // public function getMessages()
    // {
    //     // Ambil 50 pesan terbaru, urutkan dari yang paling lama ke yang terbaru
    //     $messages = Message::orderBy('created_at', 'asc')->take(50)->get();
    //     return response()->json($messages);
    // }


    // public function sendMessage(Request $request)
    // {
    //     $request->validate(['message' => 'required|max:255']);

    //     $message = Message::create([
    //         'username' => $request->session()->get('username'),
    //         'message' => $request->message,
    //     ]);

    //     broadcast(new MessageSent($message))->toOthers();

    //     return response()->json($message);
    // }

// public function sendMessage(Request $request)
// {
//     $request->validate([
//         'message' => 'required|max:255',
//     ]);

//     $message = Message::create([
//         'username' => $request->session()->get('username', 'Anonymous'),
//         'message' => $request->message,
//     ]);

//     broadcast(new MessageSent($message))->toOthers();

//     return response()->json($message);
// }

// public function getMessages()
// {
//     $messages = Message::orderBy('created_at', 'asc')->take(50)->get();
//     return response()->json($messages);
// }

public function sendMessage(Request $request)
{
    $request->validate([
        'message' => 'required|max:255',
    ]);

    $message = Message::create([
        'username' => $request->session()->get('username', 'Anonymous'),
        'message' => $request->message,
    ]);

    broadcast(new MessageSent($message))->toOthers(); // Broadcast pesan ke Pusher

    return response()->json($message);
}

public function getMessages()
{
    $messages = Message::orderBy('created_at', 'asc')->take(10)->get();
    return response()->json($messages);
}
public function getNewMessages(Request $request)
{
    $lastMessageId = $request->input('last_message_id', 0); // ID pesan terakhir yang diterima
    $newMessages = Message::where('id', '>', $lastMessageId)
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json($newMessages);
}

}