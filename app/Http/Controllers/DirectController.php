<?php

/**
 * DirectController
 * 
 * @category Direct
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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use Gate;

use App\User;

/**
 * Handles all the Direct functionality in the system
 * 
 * @category Post
 *
 * @package App\Http\Controllers
 *
 * @author Teun Strik <info@teunstrik.com>
 *
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @link none
 */
class DirectController extends Controller
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
     * The inbox page of the Direct CP
     * 
     * @return view
     */
    public function inbox()
    {
        $direct_messagesQuery = DB::table('direct_messages')->where('to_uid', Auth::user()->id)->orderBy('id', 'DESC');
        $direct_messages = $direct_messagesQuery->paginate();
        return view('direct.inbox', compact('direct_messages'));
    }
    
    /**
     * The outbox page of the Direct CP
     * 
     * @return view
     */
    public function outbox()
    {
        $direct_messagesQuery = DB::table('direct_messages')->where('from_uid', Auth::user()->id)->orderBy('id', 'DESC');
        $direct_messages = $direct_messagesQuery->paginate();
        return view('direct.outbox', compact('direct_messages'));
    }
    
    /**
     * The outbox page of the Direct CP
     *
     * @param integer $id id
     * 
     * @return view
     */
    public function view($id)
    {
        $query = DB::table('direct_messages')->where('direct_id', $id)->get();

        if (auth()->user()->id !== intval($query[0]->to_uid) && auth()->user()->id !== intval($query[0]->from_uid)) {
            return redirect((route('index')));
        }

        $query2 = DB::table('direct_views');

        foreach ($query as $row) {
            if ($query2->where([['user_id', Auth::user()->id], ['direct_id', $row->id]])->get()->isEmpty()) {
                $query2->insert(['direct_id' => $row->id, 'user_id' => Auth::user()->id]);
            }
        }

        return view('direct.view', ['rows' => $query]);
    }
    
    /**
     * The create page of the Direct CP
     *
     * @param Request $request request
     * 
     * @return view
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->only('name', 'body');
            $request->validate(
                [
                'name' => 'required|min:4|',
                'body' => 'required',
                'to_name' => 'required'
                ]
            );

               $user = User::where('name', $request["to_name"])->get();

            $query = DB::table('direct_messages');

            if ($user->isEmpty()) {
                return back();
            }

            $data["to_uid"] = $user[0]->id;

            $data["user_id"] = Auth::user()->id;

            $data["from_uid"] = Auth::user()->id;

            if ($query->insert($data)) {
                $data2 = [];
                $data2["direct_id"] = DB::getPdo()->lastInsertId();
                if ($query->where('id', DB::getPdo()->lastInsertId())->update($data2)) {
                    return redirect(route('outbox'));
                }
            }
        }

        return view('direct.create');
    }
     
    /**
     * The reply page of the Direct CP
     * 
     * @param integer $id      id
     * @param Request $request request
     * 
     * @return view
     */
    public function reply($id, Request $request)
    {
        $data = $request->only('body', 'direct_id', 'name', 'to_uid');
        $request->validate(
            [
            'body' => 'required'
            ]
        );

        $query = DB::table('direct_messages');

        $data["direct_id"] = $id;

        $data["user_id"] = Auth::user()->id;

        $data["from_uid"] = Auth::user()->id;

        if ($query->insert($data)) {
            return back();
        }
    }
}
