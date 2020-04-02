<?php

namespace App\Http\Controllers;

use App\Message;
use App\Events\MessageSent;
use App\UrlView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

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
        $ip = \Request::ip();
        $userID = Auth::check() ? Auth::id() : null;
        $data = [
            'ip_address' => $ip,
            'user_id' => $userID
        ];

        UrlView::query()->create($data);


        return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
        $messages = Message::with(array('user' => function($query){
            $query->select('id', 'name');
        }))->get();
        $beginOfDay = '';
        foreach($messages as $key => $message){
            $date = $message->created_at->format('d/m/Y');
            dd($date);
            // if(){

            // }
        }
        return ['messages' =>$messages, 'user' => Auth::user()];
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
