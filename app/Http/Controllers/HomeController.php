<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = DB::table('users')->leftjoin('posts', 'users.id', '=', 'posts.author')->paginate(10);

        return view('home', ['posts' => $posts]);
    }

    public function getPostForm() {
        return view('post/post_form');
    }

    public function createPost(Request $request){
        $postimage = $request->file('blogimage');
        $extension = $postimage->getClientOriginalExtension();
        Storage::disk('public')->put($postimage->getFilename().'.'.$extension,  File::get($postimage));
        $post = Post::create(array(
                        
            'image'=>Input::get('image'),
            'title'=>Input::get('title'),
            'description'=>Input::get('description'),
            'author'=>Auth::user()->id,
            'mime' => $postimage->getClientMimeType(),
            'original_filename' => $postimage->getClientOriginalName(),
            'filename' => $postimage->getFilename().'.'.$extension,

        ));
        return redirect()->route('home')->with('success', 'Post has been successfully added!');
    }

    public function getPost($slug){
        //$post = Post::find($id);
        $post = Post::where("slug",$slug)->first();   //uses string unlike find that uses integer
//        dd($post);
        return view('post/post_detail', ['post' => $post]);
    }

    public function editPost($id) {
        $post = Post::find($id);
        //$post = Post::where("slug",$slug)->first();
        return view('post/edit_post', ['post' => $post]);
    }

    public function updatePost(Request $request, $id) {
        $post = Post::find($id);
        //$post = Post::where("slug",$slug)->first();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();
        return redirect()->route('home')->with('success', 'Post has been updated successfully!');
    }

    public function deletePost($id) {
        $post = Post::find($id);
        //$post = Post::where("slug",$slug)->first();
        $post->delete();
        return redirect()->route('home')->with('success', 'Post has been deleted successfully!');
    }
}