<?php

/**
 * SubforumsController
 * 
 * @category Subforum
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

use App\Subforums;
use App\Role;

/**
 * Handles all the Subforum functionality in the system
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
class SubforumsController extends Controller
{
    /**
     * The show function of the SubforumsController
     * 
     * @param integer $id   id
     * @param string  $slug slug
     * 
     * @return view
     */
    public function show($id, $slug)
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

        $subforum = Subforums::where('id', '=', $id)->where('slug', '=', $slug)->firstOrFail();

        $threads = DB::table('threads')->where('subforum_id', '=', $id)->orderBy('lastpost_date', 'desc')->get();

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

        return view('subforum', ['subforum' => $subforum, 'threads' => $threads, 'users' => $users, 'count_users' => $count_users, 'count_posts' => $count_posts, 'count_topics' => $count_topics, 'last_threads' => $last_threads]);
    }

}