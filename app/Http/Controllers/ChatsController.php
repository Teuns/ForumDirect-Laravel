<?php

/**
 * ChatsController
 * 
 * @category Chat
 *
 * @package App\Http\Controllers
 *
 * @author Teun Strik <info@teunstrik.com>
 *
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @link none
 *
 * @php 7
 */
namespace App\Http\Controllers;

use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\User;

/**
 * Handles all the Chat functionality in the system
 * 
 * @category Chat
 *
 * @package App\Http\Controllers
 *
 * @author Teun Strik <info@teunstrik.com>
 *
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @link none
 */
class ChatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
        $userId = Auth::user()->id;

        $query = Message::select('*')
            ->from(DB::raw("( select id, user_id, message, created_at, type, (CASE WHEN to_uid='$userId' THEN '$userId' ELSE NULL END) as to_uid, (CASE WHEN from_uid='$userId' THEN '$userId' ELSE NULL END) as from_uid from `messages` order by id desc limit 10 ) sub "))
            ->with('user')
            ->with('user.roles')
            ->orderBy('sub.created_at');

        return $query->get();
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

        if ($request->input('type') == 'whisper') {
            $message = $user->messages()->create([
                'message' => $request->input('message'),
                'type' => $request->input('type'),
                'to_uid' => $request->input('to_uid'),
                'from_uid' => $request->input('from_uid')
            ]);
        } else { 
            $message = $user->messages()->create([
                'message' => $request->input('message'),
                'type' => $request->input('type')
            ]);
        }
        broadcast(new MessageSent($user, $message))->toOthers();
        return ['status' => 'Message Sent!'];
    }

}