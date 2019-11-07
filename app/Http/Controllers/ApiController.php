<?php

/**
 * ApiController
 * 
 * @category Api
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

use App\User;

/**
 * Handles all the Api functionality in the system
 * 
 * @category Api
 *
 * @package App\Http\Controllers
 *
 * @author Teun Strik <info@teunstrik.com>
 *
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @link none
 */
class ApiController extends Controller
{
    /**
     * Gets the username of a username correctly
     * 
     * @param string $username username
     * 
     * @return response
     */
    public function getUserNameCorrectly($username)
    {
        $query = DB::table('users')->where('name', $username)->get();

        return response($query[0]->name, 200)
                ->header('Content-Type', 'text/plain');
    }

    /**
     * Gets the role by username
     * 
     * @param string $username username
     * 
     * @return response
     */
    public function getUserRoleByName($username)
    {
        $user = DB::table('users')->where('name', $username)->get();

        $query = User::with(
            ['roles' => function ($q) use ($user) {
                $q->where('role_id', '=', $user[0]->primary_role);
            }]
        )
        ->where('id', $user[0]->id)
        ->get();

        return response($query[0]->roles[0]->name, 200)
                ->header('Content-Type', 'text/plain');
    }
    
    /**
     * Gets the UserId by the username
     * 
     * @param string $username username
     * 
     * @return response
     */
    public function getUserIdByName($username)
    {
           $query = DB::table('users')->where('name', $username)->get();

           return response($query[0]->id, 200)
                  ->header('Content-Type', 'text/plain');
    }
    
    /**
     * Gets the UserId of the user that is logged in
     * 
     * @return response
     */
    public function getUserId()
    {
           return response(Auth::user()->id, 200)
                  ->header('Content-Type', 'text/plain');
    }
    
    /**
     * Reports a thread or post
     * 
     * @param Request $request request
     * 
     * @return response
     */
    public function report(Request $request)
    {
           $report = DB::table('reports');

           $data = $request->only('type', '_id', 'reason');

           $data['from_uid'] = Auth::user()->id;

        if ($report->insert($data)) {
            return response("Success", 200)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * Likes a post or thread
     * 
     * @param Request $request request
     * 
     * @return response
     */
    public function like(Request $request)
    {
        $votes = DB::table('votes');

        $data = $request->only('pid', 'tid');

        $data['user_id'] = Auth::user()->id;
        
        if ($request->pid && !self::CheckIfUserVotedPost($request->pid) && !self::checkIfUserIsPostOwner($request->pid)) {
            $post = DB::table('posts')->where('id', $request->pid);
            if ($votes->insert($data) && $post->update(['votes' => DB::raw('votes+1')])) {
                return response("Success", 200)
                    ->header('Content-Type', 'text/plain');
            }
        } else {
            if ($request->tid && !self::CheckIfUserVotedThread($request->tid) && !self::checkIfUserIsThreadOwner($request->tid)) {
                $thread = DB::table('threads')->where('id', $request->tid);
                if ($votes->insert($data) && $thread->update(['votes' => DB::raw('votes+1')])) {
                    return response("Success", 200)
                        ->header('Content-Type', 'text/plain');
                }
            }
        }
    }
    
    /**
     * Gets the recent posts
     * 
     * @return view
     */
    public function getRecentPosts()
    {
        $last_threads = DB::table('threads')
            ->orderBy('threads.lastpost_date', 'desc')
            ->limit(10)
            ->where('published', '=', 1)
            ->get();


        return view('api/recent', ['last_threads' => $last_threads]);
    }
    
    /**
     *  Checks if user is the owner of the given thread
     * 
     * @param integer $tid threadId
     * 
     * @return boolean
     */
    public function checkIfUserIsThreadOwner($tid)
    {
        $threads = DB::table('threads');
        $query = $threads->where('id', '=', $tid)->where('user_id', '=', Auth::user()->id)->get();

        if (!$query->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Checks if user is the owner of the given post
     * 
     * @param integer $pid postId
     * 
     * @return boolean
     */
    public function checkIfUserIsPostOwner($pid)
    {
        $posts = DB::table('posts');
        $query = $posts->where('id', '=', $pid)->where('user_id', '=', Auth::user()->id)->get();

        if (!$query->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Checks if user votes thread
     * 
     * @param integer $tid threadId
     *
     * @return boolean
     */
    public function checkIfUserVotedThread($tid)
    {
        $votes = DB::table('votes');
        $query = $votes->where('tid', '=', $tid)->where('user_id', '=', Auth::user()->id)->get();

        if (!$query->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if user voted post
     * 
     * @param integer $pid postId
     * 
     * @return boolean
     */
    public function checkIfUserVotedPost($pid)
    {
        $votes = DB::table('votes');
        $query = $votes->where('pid', '=', $pid)->where('user_id', '=', Auth::user()->id)->get();

        if (!$query->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }

}
