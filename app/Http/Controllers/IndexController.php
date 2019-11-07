<?php

/**
 * IndexController
 * 
 * @category Index
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
use \App\Http\Controllers\ThreadController;

use Auth;

use App\User;
use App\Role;

/**
 * Handles all the Index functionality in the system
 * 
 * @category Category
 *
 * @package App\Http\Controllers
 *
 * @author Teun Strik <info@teunstrik.com>
 *
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @link none
 */
class IndexController extends Controller
{
    /**
     * The index page of the Index
     * 
     * @param Request $request request
     * 
     * @return view
     */
    public function index(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user();

            $user->user_timestamp = now();
            $user->save();

            $warnings = DB::table('warnings')
                ->select(DB::raw('SUM(percentage) AS percentageTotal'))
                ->whereRaw('to_user_id = ? && valid_until >= NOW()', Auth::user()->id)
                ->get();

            $count_warnings = DB::table('warnings')->where('to_user_id', Auth::user()->id)->count();
            $role = Role::where('name', '=', 'Banned')->first();

            if ($warnings[0]->percentageTotal >= 100) {
                if ($user->primary_role !== $role->id) {
                    $member_Role = Role::where('name', '=', 'Member')->first();
                    $user_roles = DB::table('role_users')->where('user_id', Auth::user()->id);
                    if ($user_roles->where('role_id', $role->id)->get()->isEmpty()) {
                        $user = auth::user();
                        $user->primary_role = $role->id;
                        $user->save();
                        $user->roles()->detach($member_Role);
                        $user->roles()->attach($role);
                    } else {
                        $user = auth::user();
                        $user->primary_role = $role->id;
                        $user->save();
                        $user->roles()->detach($member_Role);
                    }
                }     
            } elseif ($count_warnings && $user->primary_role == $role->id) {
                $member_Role = Role::where('name', '=', 'Member')->first();
                $role = Role::where('name', '=', 'Banned')->first();
                $user = auth::user();
                $user->primary_role = $member_Role->id;
                $user->save();
                $user->roles()->detach($role);
                $user->roles()->attach($member_Role);
            }
        }

        $forums = DB::table('forums')
            ->join('subforums', 'forums.id', '=', 'subforums.forum_id')
            ->select('forums.*', 'forums.id AS cat_id', 'forums.name AS cat_name', 'subforums.id AS subcat_id', 'subforums.slug AS slug', 'subforums.name', 'subforums.description')
            ->orderBy('forums.id', 'asc')
            ->orderBy('subforums.pos', 'asc')
            ->get();

        $users = DB::table('users')
            ->orderBy('id', 'desc')
            ->get();

        $last_threads = DB::table('threads')
            ->orderBy('threads.lastpost_date', 'desc')
            ->limit(10)
            ->where('published', '=', 1)
            ->get();

        $count_posts = DB::table('posts')->count();

        $count_topics = DB::table('threads')->count();

        $count_users = DB::table('users')->count();

        $chatbox = view('chat', ['userToken' => session()->getId()])->render();

        $count_reports = DB::table('reports')->count();

        $count_drafts_threads = DB::table('threads')->where('published', '=', false)->count();

        $count_drafts_posts = DB::table('posts')->where('published', '=', false)->count();

        if (Auth::user()) {
            $direct_messagesQuery = DB::table('direct_messages')->where('to_uid', Auth::user()->id)->whereNotIn(
                'direct_messages.id', function ($query) {
                    $query->select('direct_id')
                        ->from('direct_views')
                        ->where('user_id', Auth::user()->id);
                }
            )->get();
        } else {
            $direct_messagesQuery = null;
        }

        return view('index', ['direct_messagesQuery' => $direct_messagesQuery, 'forums' => $forums, 'count_reports' => $count_reports, 'count_users' => $count_users, 'count_posts' => $count_posts, 'count_topics' => $count_topics, 'count_drafts_threads' => $count_drafts_threads, 'count_drafts_posts' => $count_drafts_posts, 'users' => $users, 'last_threads' => $last_threads, 'chatbox' => $chatbox]);
    }

    /**
     * The getLastPost function
     * 
     * @param integer $id id
     * 
     * @return string
     */
    static function getLastPost($id)
    {
        $threads = DB::table('threads');

        $query = $threads->where('subforum_id', $id)
            ->where('published', '=', 1)
            ->orderBy('lastpost_date', 'desc')
            ->get();

        if (!$query->isEmpty()) {
            $html = "<a href='/threads/show/".$query[0]->id."-".$query[0]->slug."?action=lastpost'>".str_limit($query[0]->title, 50)."</a>
                <div>by <a href='/users/".ThreadController::getUserName($query[0]->lastpost_uid)."' class='".strtolower(ThreadController::getUserRoleName($query[0]->lastpost_uid))."'>".ThreadController::getUserName($query[0]->lastpost_uid)."</a></div>
                <div>".$query[0]->lastpost_date."</div>";
            return $html;
        } else {
            return '-';
        }
    }

    /**
     * The getLastPostByThread function
     * 
     * @param integer $id id
     * 
     * @return string
     */
    static function getLastPostByThread($id)
    {
        $threads = DB::table('threads');

        $query = $threads->where('id', $id)
            ->where('published', '=', 1)
            ->orderBy('lastpost_date', 'desc')
            ->get();

        if (!$query->isEmpty()) {
             $html = "<a href='/threads/show/".$query[0]->id."-".$query[0]->slug."?action=lastpost'>".str_limit($query[0]->title, 50)."</a>
                <div>by <a href='/users/".ThreadController::getUserName($query[0]->lastpost_uid)."' class='".strtolower(ThreadController::getUserRoleName($query[0]->lastpost_uid))."'>".ThreadController::getUserName($query[0]->lastpost_uid)."</a></div>
                <div>".$query[0]->lastpost_date."</div>";
            return $html;
        } else {
            return '-';
        }
    }

    /**
     * The countSubforumPosts function
     * 
     * @param integer $id id
     * 
     * @return integer
     */
    static function countSubforumPosts($id)
    {
        $count_posts = DB::table('posts')->where('subforum_id', $id)->where('published', 1)->count();
        
        return $count_posts;
    }

    /**
     * The countSubforumThreads function
     * 
     * @param integer $id id
     * 
     * @return integer
     */
    static function countSubforumThreads($id)
    {
        $count_threads = DB::table('threads')->where('subforum_id', $id)->where('published', 1)->count();

        return $count_threads;
    }
    
    /**
     * The getUserName function
     * 
     * @param integer $userId id
     * 
     * @return integer
     */
    static function getUserName($userId)
    {
        $users = DB::table('users');
        $query = $users->where('id', $userId)->get();

        return $query[0]->name;
    }
    
    /**
     * The getUserRoleName function
     * 
     * @param integer $userId id
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
    
}