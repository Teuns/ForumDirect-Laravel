<?php

/**
 * UsersController
 * 
 * @category User
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
 * Handles all the Users functionality in the system
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
class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['profile']]);
    }

    /**
     * The index page of the Users CP
     * 
     * @return view
     */
    public function index()
    {
        $user = Auth::user();

        $threads = DB::table('threads')->where('user_id', '=', $user->id)->get();

        $count_posts = DB::table('posts')->where('user_id', '=', $user->id)->count();

        $count_threads = DB::table('threads')->where('user_id', '=', $user->id)->count();

        return view('users/index', ['user' => $user, 'count_posts' => $count_posts, 'count_threads' => $count_threads, 'threads' => $threads]);

    }
    
    /**
     * The editAccount page of the Users CP
     * 
     * @param Request $request request
     * 
     * @return view
     */
    public function editAccount(Request $request)
    {
        $users = User::where('id', '=', Auth::user()->id)->firstOrFail();

        $roles = User::with(
            ['roles' => function ($q) {
                $q->where('user_id', '=', Auth::user()->id);
            }]
        )
        ->where('id', Auth::user()->id)
        ->get();

        if ($request->isMethod('post')) {
            $data = $request->only('primary_role', 'email', 'password', 'sex', 'date_of_birth', 'display_date_of_birth', 'bio');
            if ($data['email'] !== Auth::user()->email) {
                $data = $request->only('password', 'primary_role', 'sex', 'date_of_birth', 'display_date_of_birth', 'bio');
            }
            if ($data['password']) {
                $request->validate(
                    [
                    'email' => '|required|min:10|'
                    ]
                );
                $data['password'] = bcrypt($data['password']);
            } else {
                $data = $request->only('email', 'primary_role', 'sex', 'date_of_birth', 'display_date_of_birth', 'bio');
            }

            if ($data['primary_role']) {
                $query = User::with(
                    ['roles' => function ($q) use ($data) {
                        $q->where('role_id', '=', $data['primary_role']);
                    }]
                )
                ->where('id', Auth::user()->id)->get();

                if ($query[0]->roles->isEmpty()) {
                    return back();
                }
            }
            $users->fill($data)->save();
            return back();
        }

        return view('users/edit-account', ['roles' => $roles]);
    }
     
    /**
     * The uploadAvatar page of the Users CP
     * 
     * @param Request $request request
     * 
     * @return view
     */
    public function uploadAvatar(Request $request) 
    {
        if ($request->isMethod('post')) {
            $this->validate(
                $request, [
                'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]
            );

            $image = $request->file('input_img');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            $query = DB::table('users')
                ->where('id', Auth::user()->id)
                ->update(['user_avatar' => '/images/'.$name]);

               return back()->with('success', 'Image Upload successfully');
        }

        $user = Auth::user();

        return view('users/upload-avatar', ['user' => $user]);
    }
    
    /**
     * The profile page of the Users CP
     * 
     * @param integer $userName userName
     * 
     * @return view
     */
    public function profile($userName)
    {
        $user = User::where('name', '=', $userName)->firstOrFail();

        $count_posts = DB::table('posts')->where('user_id', '=', $user->id)->count();

        $count_threads = DB::table('threads')->where('user_id', '=', $user->id)->count();

        return view('users/profile', ['user' => $user, 'count_posts' => $count_posts, 'count_threads' => $count_threads]);
    }
    
    /**
     * The getUserRoleName function of the Users CP
     * 
     * @param integer $userId userId
     * 
     * @return view
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