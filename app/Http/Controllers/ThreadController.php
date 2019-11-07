<?php

/**
 * ThreadController
 * 
 * @category Thread
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
use App\Http\Requests\StoreThread as StoreThreadRequest;
use App\Http\Requests\UpdateThread as UpdateThreadRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

use App\Thread;
use App\User;
use App\Role;

use Auth;
use Gate; 

/**
 * Handles all the Thread functionality in the system
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
class ThreadController extends Controller
{
    /**
     * The show function of the ThreadController
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

            $views = DB::table('threads_views');

            if ($views->where('user_id', '=', Auth::user()->id)->where('thread_id', '=', $id)->get()->isEmpty()) {
                $data['user_id'] = Auth::user()->id;
                $data['thread_id'] = $id;
                $views->insert($data);
            }
        }

        $thread = Thread::published()->where('id', '=', $id)->where('slug', '=', $slug)->firstOrFail();
        $posts = DB::table('posts')->where('thread_id', $thread->id)->where('published', 1)->paginate(10);

        $threads = DB::table('threads')->where('id', '=', $id);

        $threads->update(['views' => DB::raw('views+1')]);

        $index = $posts->firstItem();

        $page = $posts->currentPage();

        $subforum = DB::table('subforums')->where('id', $thread->subforum_id)->get();

        $last_threads = DB::table('threads')
            ->orderBy('threads.lastpost_date', 'desc')
            ->limit(10)
            ->where('published', '=', 1)
            ->get();

        $users = DB::table('users')
            ->orderBy('id', 'desc')
            ->get();

        $views = DB::table('threads_views')->where('thread_id', '=', $thread->id)->get();

        if (request()->has('action') && request()->action == 'lastpost') {
            $lastPage = $posts->lastPage();

            $posts = DB::table('posts')->where('thread_id', $thread->id)->where('published', 1)->get();

            $lastPostId = $posts->last();

            if ($lastPage > 1) {
                $url = URL::route('show_thread', ['id' => $id, 'slug' => $slug, 'page' => $lastPage])."#pid".$lastPostId->id;
                return \Redirect::to($url);
            } elseif ($lastPostId) {
                $url = URL::route('show_thread', ['id' => $id, 'slug' => $slug])."#pid".$lastPostId->id;
                return \Redirect::to($url);
            } else {
                return redirect(action('ThreadController@show', ['id' => $id, 'slug' => $slug]));
            }
        }

        return view('threads.show', ['thread' => $thread, 'views' => $views, 'posts' => $posts, 'index' => $index + 1, 'page' => $page, 'subforum' => $subforum, 'last_threads' => $last_threads, 'users' => $users]);
    }
    
    /**
     * The create page of the ThreadsController
     * 
     * @param Request $request request
     * @param integer $id      id
     * 
     * @return view
     */
    public function create(Request $request, $id)
    {
        $draft = config('app.draft');
        $subforums = DB::table('subforums');
        $query = $subforums->where('id', $id)->get();

        if ($query->isEmpty()) {
            return redirect()->route('index')->with('alert-danger', 'Subforum does not exist.');
        }

        if ($request->has('id')) {
            $thread = DB::table('threads')->where('id', '=', $request->id)->get();

            if ($thread->isEmpty()) {
                return redirect()->route('index')->with('alert-danger', 'Thread does not exist.');
            } else {
                $author = DB::table('users')->where('id', '=', $thread[0]->user_id)->get();
                return view('posts.create', ['draft' => $draft, 'id' => $thread[0]->id, 'subforum_id' => $id, 'thread' => $author[0]->name."\n".$thread[0]->body]);    
            }
        } else {
            return view('threads.create', ['draft' => $draft, 'id' => $id]); 
        }
    }
    
    /**
     * The store function of the Threadscontroller
     * 
     * @param StoreThreadRequest $request request
     * 
     * @return redirect
     */
    public function store(StoreThreadRequest $request)
    {
        $data = $request->only('title', 'body');
        $data['slug'] = Str::slug($data['title'], '-');
        $data['user_id'] = Auth::user()->id;
        $data['subforum_id'] = $request->id;
        $data['lastpost_uid'] = Auth::user()->id;
        $data['lastpost_date'] = now();

        $draft = config('app.draft');

        if (strtolower(self::getUserRoleName(Auth::user()->id)) === 'administrator' || strtolower(self::getUserRoleName(Auth::user()->id)) === 'moderator' || $draft == false) {
            $data['updated_at'] = null;
            $data['published'] = 1;
        }

        $thread = Thread::create($data);

        return redirect()->route('index')->with('alert', 'Thread saved successfully.');
    }
    
    /**
     * The edit page of the Threadscontroller
     * 
     * @param Thread $thread thread
     * 
     * @return view
     */
    public function edit(Thread $thread)
    {
        return view('threads.edit', compact('thread'));
    }

    /**
     * The update page of the Threadscontroller
     * 
     * @param Thread              $thread  thread
     * @param UpdateThreadRequest $request request
     * 
     * @return redirect
     */
    public function update(Thread $thread, UpdateThreadRequest $request)
    {
        $data = $request->only('title', 'body');
        $data['slug'] = str_slug($data['title']);
        $thread->fill($data)->save();
        return redirect()->route('index')->with('alert', 'Thread successfully updated.');
    }
    
    /**
     * The drafts page of the Threadscontroller
     * 
     * @return view
     */
    public function drafts()
    {
        $threadsQuery = Thread::unpublished();
        $threads = $threadsQuery->paginate();
        return view('threads.drafts', compact('threads'));
    }
    
    /**
     * The publish function of the Threadscontroller
     * 
     * @param Thread $thread thread
     * 
     * @return back
     */
    public function publish(Thread $thread)
    {
        $thread->updated_at = null;
        $thread->published = true;
        $thread->save();
        return back();
    }
    
    /**
     * The getUserName function of the Threadscontroller
     * 
     * @param integer $userId userId
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
     * The getUserRoleName function of the Threadscontroller
     * 
     * @param integer $userId userId
     * 
     * @return integer
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
     * The getUserAvatar function of the Threadscontroller
     * 
     * @param integer $userId userId
     * 
     * @return integer
     */
    static function getUserAvatar($userId)
    {
        $users = DB::table('users');
        $query = $users->where('id', $userId)->get();

        return $query[0]->user_avatar;
    }
    
    /**
     * The getUserTags function of the Threadscontroller
     * 
     * @param string $string string
     * 
     * @return string
     */
    static function getUserTags($string)
    {
            $pattern = '/@([^ ]\w*)/';

            $result = preg_replace_callback(
                $pattern, function ($matches) {
                    $replacement = false;
                    foreach ($matches as $key => $value) {
                        $users = DB::table('users');

                        $user = $users->where('name', $matches[1])->get();

                        if (!$user->isEmpty()) {
                            $replacement = '<a href="/users/'.$user[$key]->name.'" class="'.strtolower(self::getUserRoleName($user[$key]->id)).'">@'.$user[$key]->name.'</a>';
                        } else {
                            $replacement = '@'.$matches[1];
                        }

                        return $replacement;
                    }
                }, $string
            );

            return $result;
    }
    
    /**
     * The checkIfUserVotedThread function of the Threadscontroller
     * 
     * @param integer $tid tid
     * 
     * @return boolean
     */
    static function checkIfUserVotedThread($tid)
    {
        $votes = DB::table('votes');
        $query = $votes->where('tid', '=', $tid)->where('user_id', '=', Auth::user()->id)->get();

        if (!$query->isEmpty()) {
            return 'disabled="true"';
        } else {
            return false;
        }
    }
    
    /**
     * The checkIfUserVotedPost function of the Threadscontroller
     * 
     * @param integer $pid pid
     * 
     * @return boolean
     */
    static function checkIfUserVotedPost($pid)
    {
        $votes = DB::table('votes');
        $query = $votes->where('pid', '=', $pid)->where('user_id', '=', Auth::user()->id)->get();

        if (!$query->isEmpty()) {
            return 'disabled="true"';
        } else {
            return false;
        }
    }
    
}