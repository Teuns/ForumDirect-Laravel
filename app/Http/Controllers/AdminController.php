<?php

/**
 * AdminController
 * 
 * @category Admin
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
use Illuminate\Support\Str;

use App\User;
use App\Role;
use Auth;

/**
 * Handles all the Admin functionality in the system
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
class AdminController extends Controller
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
     * Gets the Role name of a UserId
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
     * Index of the Admin CP
     * 
     * @return response
     */
    public function index()
    {
        $count_messages = DB::table('messages')->count();

        $count_posts = DB::table('posts')->count();

        $count_topics = DB::table('threads')->count();

        $count_users = DB::table('users')->count();

        $count_reports = DB::table('reports')->count();

        if (strtolower(self::getUserRoleName(Auth::user()->id)) == 'administrator') {
            return view('admin/index', ['count_messages' => $count_messages, 'count_posts' => $count_posts, 'count_topics' => $count_topics, 'count_users' => $count_users, 'count_reports' => $count_reports]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Users page of the Admin CP
     * 
     * @return response
     */
    public function users()
    {
        $users = DB::table('users')->get();

        if (strtolower(self::getUserRoleName(Auth::user()->id)) == 'administrator') {
            return view('admin/users', ['users' => $users]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * Forums page of the Admin CP
     * 
     * @return response
     */
    public function forums()
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) == 'administrator') {
            $forums = DB::table('forums')
                ->join('subforums', 'forums.id', '=', 'subforums.forum_id')
                ->select('forums.*', 'forums.id AS cat_id', 'forums.name AS name', 'subforums.id AS subforum_id', 'subforums.slug AS slug', 'subforums.name AS subforum_name', 'subforums.description')
                ->orderBy('forums.id', 'asc')
                ->orderBy('subforums.pos', 'asc')
                ->get();

            $forums_without_subforums = DB::table('forums')
                ->leftJoin('subforums', 'forums.id', '=', 'subforums.forum_id')
                ->select('forums.*')
                ->where('subforums.forum_id', '=', null)
                ->get();

            return view('admin/forums', ['forums' => $forums, 'forums_without_subforums' => $forums_without_subforums]);
        } else {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * Create Forum page of the Admin CP
     * 
     * @param Request $request request
     * 
     * @return redirect
     */
    public function createForum(Request $request)
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) !== 'administrator') {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }

        $forums = DB::table('forums');

        if ($request->isMethod('post')) {
            $request->validate(
                [
                'name' => 'min:3'
                ]
            );

            $data = $request->only('name');

            $forums->insert($data);

            return redirect()->route('admincp_forums');
        }

        return view('admin/create-forum');
    }
    
    /**
     * Edit Forum page of the Admin CP
     * 
     * @param Request $request request 
     * @param integer $id      id
     * 
     * @return back
     */
    public function editForum(Request $request, $id)
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) !== 'administrator') {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }

        $forum = DB::table('forums')->where('id', '=', $id);

        if ($request->isMethod('post')) {
            $request->validate(
                [
                'name' => 'min:3'
                ]
            );

            $data = $request->only('name');

            $forum->update($data);

            return back();
        }

        return view('admin/edit-forum', ['forum' => $forum->get()]);
    }

    /**
     * The delete forum page of the Admin CP
     * 
     * @param Request $request request
     * @param integer $id      id
     * 
     * @return back
     */
    public function deleteForum(Request $request, $id)
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) !== 'administrator') {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }

        $forum = DB::table('forums')->where('id', '=', $id);

        $forum->delete();

        return back();
    }
    
    /**
     * The get Forum Name function of the Admin CP
     * 
     * @param integer $id id
     * 
     * @return string
     */
    static function getForumName($id)
    {
        $forums = DB::table('forums');
        $query = $forums->where('id', $id)->get();

        return $query[0]->name;
    }

    /**
     * The createSubforum page of the Admin CP
     * 
     * @param Request $request request
     * 
     * @return redirect
     */
    public function createSubforum(Request $request)
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) !== 'administrator') {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }

        $forums = DB::table('forums')->get();

        $subforums = DB::table('subforums');

        if ($request->isMethod('post')) {
            $request->validate(
                [
                'name' => 'min:3'
                ]
            );

            $data = $request->only('name', 'slug', 'description', 'forum_id', 'pos');

            $data['slug'] = Str::slug($data['name'], '-');

            $subforums->insert($data);

            return redirect()->route('admincp_forums');
        }

        return view('admin/create-subforum', ['forums' => $forums]);
    } 

    /**
     * The editSubforum page of the Admin CP
     * 
     * @param Request $request request
     * @param integer $id      id
     * 
     * @return view
     */
    public function editSubforum(Request $request, $id)
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) !== 'administrator') {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }

        $forums = DB::table('forums')->get();

        $subforum = DB::table('subforums')->where('id', '=', $id);

        if ($request->isMethod('post')) {
            $request->validate(
                [
                'name' => 'min:3'
                ]
            );

            $data = $request->only('name', 'slug', 'description', 'forum_id', 'pos');

            $data['slug'] = Str::slug($data['name'], '-');

            $subforum->update($data);

            return back();
        }

        return view('admin/edit-subforum', ['subforum' => $subforum->get(), 'forums' => $forums]);
    }

    /**
     * The deleteSubforum page of the Admin CP
     * 
     * @param Request $request request
     * @param integer $id      id
     * 
     * @return back
     */
    public function deleteSubforum(Request $request, $id)
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) !== 'administrator') {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }

        $subforum = DB::table('subforums')->where('id', '=', $id);

        $subforum->delete();

        return back();
    }
    
    /**
     * The edit account page of the Admin CP
     * 
     * @param Request $request request
     * @param integer $userId  userId
     * 
     * @return view
     */
    public function editAccount(Request $request, $userId)
    {
        if (strtolower(self::getUserRoleName(Auth::user()->id)) !== 'administrator') {
            return response('Forbidden', 403)
                ->header('Content-Type', 'text/plain');
        }

        $user = User::where('id', '=', $userId)->with('roles')->firstOrFail();

        if ($request->isMethod('post')) {
            $request->validate(
                [
                'email' => 'required',
                'name' => 'min:3',
                ]
            );

            $data = $request->only('email', 'name', 'avatar', 'role', 'primary_role');
            $role = Role::where('name', '=', $data['role'])->first();
            
            if ($role) {
                $user->roles()->sync($role);
                $data['primary_role'] = $role->id;
            }      
            $user->user_avatar = $data['avatar'];
            $user->fill($data)->save();

            // return back();
        }

        return view('admin/edit-account', ['user' => $user]);
    }

}