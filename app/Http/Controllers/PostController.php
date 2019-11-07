<?php

/**
 * PostController
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
use App\Http\Requests\StorePost as StorePostRequest;
use App\Http\Requests\UpdatePost as UpdatePostRequest;
use Illuminate\Support\Facades\DB;

use \App\Http\Controllers\ThreadController; 

use Auth;
use Gate;

use App\Post;
use App\User;

/**
 * Handles all the Post functionality in the system
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
class PostController extends Controller
{
   
    /**
     * The create page of the PostController
     * 
     * @param Request $request request
     * @param integer $id      id
     * 
     * @return view
     */
    public function create(Request $request, $id)
    {
        $draft = config('app.draft');
        $threads = DB::table('threads');
        $query = $threads->where('id', $id)->get();

        if ($query->isEmpty()) {
            return redirect()->route('index')->with('alert-danger', 'Topic does not exist.');
        }

        if (!$query[0]->closed or ThreadController::getUserRoleName(Auth::user()->id) == 'Administrator' or ThreadController::getUserRoleName(Auth::user()->id) == 'Moderator') {
            if ($request->has('id')) {
                $post = DB::table('posts')->where('id', '=', $request->id)->get();

                if ($post->isEmpty()) {
                    return redirect()->route('index')->with('alert-danger', 'Post does not exist.');
                } else {
                    $author = DB::table('users')->where('id', '=', $post[0]->user_id)->get();

                    return view('posts.create', ['draft' => $draft, 'id' => $id, 'subforum_id' => $query[0]->subforum_id, 'post' => $author[0]->name."\n".$post[0]->body]);
                }
            } else {
                return view('posts.create', ['draft' => $draft, 'id' => $id, 'subforum_id' => $query[0]->subforum_id]);
            }
        } else {
            return redirect()->route('index')->with('alert-danger', 'Topic is closed.');
        }

    }
    
    /**
     * The store function of the PostController
     * 
     * @param Request $request request
     * 
     * @return view
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->only('body');
        $data['thread_id'] = $request->id;
        $data['user_id'] = Auth::user()->id;
        $data['subforum_id'] = $request->subforum_id;

        $draft = config('app.draft');

        if (ThreadController::getUserRoleName(Auth::user()->id) === 'Administrator' || ThreadController::getUserRoleName(Auth::user()->id) === 'Moderator' || $draft == false) {
            $data['updated_at'] = null;
            $data['published'] = 1;
            $query = DB::table('threads')
                ->where('id', $request->id)
                ->update(['lastpost_uid' => Auth::user()->id, 'lastpost_date' => now()]);
        }

        $post = Post::create($data);

        return redirect()->route('index')->with('alert', 'Post has been saved successfully.');
    }
    
    /**
     * The drafts page of the PostController
     * 
     * @return view
     */
    public function drafts()
    {
        $postsQuery = Post::unpublished();
        $posts = $postsQuery->paginate();
        return view('posts.drafts', compact('posts'));
    }
     
    /**
     * The edit function of the PostController
     * 
     * @param Post $post post
     * 
     * @return view
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
    
    /**
     * The update function of the PostController
     * 
     * @param Post              $post    post
     * @param UpdatePostRequest $request request
     * 
     * @return view
     */
    public function update(Post $post, UpdatePostRequest $request)
    {
        $data = $request->only('body');
        $post->fill($data)->save();
        return redirect()->route('index')->with('alert', 'Post successfully updated.');
    }
    
    /**
     * The publish function of the PostController
     * 
     * @param Post $post post
     * 
     * @return view
     */
    public function publish(Post $post)
    {
        $post->updated_at = null;

        $post->published = true;

        $query = DB::table('threads')
            ->where('id', $post->thread_id)
            ->update(['lastpost_uid' => $post->user_id, 'lastpost_date' => $post->created_at]);

        $post->save();

        return back();
    }

}