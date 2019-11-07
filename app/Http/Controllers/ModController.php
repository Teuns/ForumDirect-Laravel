<?php

/**
 * ModController
 * 
 * @category Mod
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
use App\Post;
use App\Thread;

/**
 * Handles all the Mod functionality in the system
 * 
 * @category Mod
 *
 * @package App\Http\Controllers
 *
 * @author Teun Strik <info@teunstrik.com>
 *
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @link none
 */
class ModController extends Controller
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
     * The getUserRoleName function of the ModController
     * 
     * @param integer $userId userId
     *
     * @return string
     */
    static function getUserRoleName($userId)
    {
        $user = User::where('id', $userId)->get();

        $query = User::with(
            ['roles' => function ($q) use ($user) {
                $q->where('role_id', '=', $user[0]->primary_role);
            }]
        )
        ->where('id', $userId)
        ->get();

        return $query[0]->roles[0]->name;
    }
    
    /**
     * The index page of the Mod CP
     * 
     * @return response
     */
    public function index()
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $reports = DB::table('reports')->orderBy('id', 'desc')->get();

            $warnings = DB::table('warnings')->orderBy('id', 'desc')->get();

            $users = DB::table('users')->get();

            return view('mod/index', ['reports' => $reports, 'warnings' => $warnings, 'users' => $users]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The warnUser page of the Mod CP
     * 
     * @param Request $request request
     * 
     * @return view
     */
    public function warnUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->only('reason', 'percentage', 'valid_until');
            $request->validate(
                [
                'reason' => 'required',
                'percentage' => 'required',
                'to_name' => 'required',
                'valid_until' => 'required'
                ]
            );

            $user = User::where('name', $request["to_name"])->get();

            $query = DB::table('warnings');

            $query2 = DB::table('direct_messages');

            if ($user->isEmpty()) {
                return back();
            }

            $data["to_user_id"] = $user[0]->id;

            $data["from_user_id"] = Auth::user()->id;

            if ($query->insert($data)) {
                $data2 = [];
                $data2["name"] = "You have been warned for: ".$data["reason"];
                $data2["body"] = "This warning is valid until: ".$data["valid_until"].". Percentage: ".$data["percentage"]."%";
                $data2["to_uid"] = $user[0]->id;
                $data2["from_uid"] = Auth::user()->id;
                $data2["user_id"] = Auth::user()->id;
                if ($query2->insert($data2)) {
                    $data3 = [];
                    $data3["direct_id"] = DB::getPdo()->lastInsertId();
                    if ($query2->where('id', DB::getPdo()->lastInsertId())->update($data3)) {
                        return redirect(action('ModController@index'));
                    }
                }
            }
        }

        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            return view('mod.warn_user');
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The banUser function of the Mod CP
     * 
     * @param integer $id id
     * 
     * @return view
     */
    public function banUser($id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $user = DB::table('users')->where('id', '=', $id);
            $role_user = DB::table('role_users')->where('user_id', '=', $id);

            if ($role_user->update(['role_id' => 3])) {
                if ($user->update(['primary_role' => 3])) {
                    return redirect(action('ModController@index'));
                }
            }
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The unbanUser function of the Mod CP
     * 
     * @param integer $id id
     * 
     * @return view
     */
    public function unbanUser($id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $user = DB::table('users')->where('id', '=', $id);
            $role_user = DB::table('role_users')->where('user_id', '=', $id);

            if ($role_user->update(['role_id' => 1])) {
                if ($user->update(['primary_role' => 1])) {
                    return redirect(action('ModController@index'));
                }
            }
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The report page of the Mod CP
     * 
     * @param integer $id id
     * 
     * @return view
     */
    public function report($id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $report = DB::table('reports')->where('id', '=', $id)->get();

            return view('mod/report', ['report' => $report]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The deleteReport function of the Mod CP
     * 
     * @param integer $id id
     * 
     * @return redirect
     */
    public function deleteReport($id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $report = DB::table('reports')->where('id', '=', $id);

            if ($report->delete()) {
                return redirect(action('ModController@index'));
            }
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The deletePost function of the Mod CP
     * 
     * @param integer $id id
     * 
     * @return redirect
     */
    public function deletePost($id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $post = DB::table('posts')->where('id', '=', $id);

            if ($post->delete()) {
                return redirect(action('ModController@index'));
            } else {
                return response('Post Probably Already Deleted', 503)
                    ->header('Content-Type', 'text/plain');
            }
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The deleteThread function of the Mod CP
     * 
     * @param integer $id id
     * 
     * @return redirect
     */
    public function deleteThread($id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $thread = DB::table('threads')->where('id', '=', $id);

            if ($thread->delete()) {
                return redirect(action('ModController@index'));
            } else {
                return response('Thread Probably Already Deleted', 503)
                    ->header('Content-Type', 'text/plain');
            }
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The getThread function of the Mod CP
     * 
     * @param integer $tid tid
     * 
     * @return string
     */
    static function getThread($tid)
    {
        $threads = DB::table('threads');

        $query = $threads->where('id', '=', $tid)->get();

        if (!$query->isEmpty()) {
            return $query[0]->body;
        } else {
            return "Thread doesn't exist anymore.";
        }
    }
    
    /**
     * The getPost function of the Mod CP
     * 
     * @param integer $pid pid
     * 
     * @return string
     */
    static function getPost($pid)
    {
        $posts = DB::table('posts');

        $query = $posts->where('id', '=', $pid)->get();

        if (!$query->isEmpty()) {
            return $query[0]->body;
        } else {
            return "Post doesn't exist anymore.";
        }
    }
    
    /**
     * The posts page of the Mod CP
     * 
     * @return view
     */
    public function posts()
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $posts = DB::table('posts');

            $query = $posts->get();

            return view('mod/posts', ['posts' => $query]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The editPost page of the Mod CP
     * 
     * @param Request $request request
     * @param intger  $id      id
     * 
     * @return view
     */
    public function editPost(Request $request, $id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $post = Post::where('id', '=', $id)->firstOrFail();

            if ($request->isMethod('post')) {
                $data = $request->only('body');
                $post->fill($data)->save();
                return back();
            }

            return view('mod/edit_post', ['post' => $post]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The threads page of the Mod CP
     * 
     * @param Request $request request
     * 
     * @return view
     */
    public function threads()
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $threads = DB::table('threads');

            $query = $threads->get();

            return view('mod/threads', ['threads' => $query]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * The editThread page of the Mod CP
     * 
     * @param Request $request request
     * @param intger  $id      id
     * 
     * @return view
     */
    public function editThread(Request $request, $id)
    {
        if (strtolower(self::GetUserRoleName(Auth::user()->id)) == 'administrator' || strtolower(self::GetUserRoleName(Auth::user()->id)) == 'moderator') {
            $thread = Thread::where('id', '=', $id)->firstOrFail();

            if ($request->isMethod('post')) {
                $data = $request->only('title', 'body', 'closed');
                $thread->fill($data)->save();
                return back();
            }

            return view('mod/edit_thread', ['thread' => $thread]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
}