<?php

namespace App\Http\Controllers;

use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ChatsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
        $message = Message::with('user')->get();
        return ['messages' =>$message];
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);
            
        broadcast(new MessageSent($user, $message))->toOthers();
        $status = 'success';
        return response()->json(compact(['status']), 200);
    }

}
