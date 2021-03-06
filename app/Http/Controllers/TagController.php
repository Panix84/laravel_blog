<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tag;
use App\Post;
use Session;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware('admin', ['except' => 'show']);
    }

    public function index()
    {
        $tags = Tag::all();

        return view('tags.index')->withTags($tags);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save a new category and then redirect back to index
        $this->validate($request, array(
                'name' => 'required|max:255',
                'slug' => 'required|alpha_dash|min:5|max:255|unique:tags,slug',
                'meta_title'     => 'required|max:70',
                'meta_desscription' => 'required|max:160',
                'meta_keywords'  => 'required'
            ));

        $tag = new Tag;

        $tag->name = $request->name;
        $tag->slug = str_slug($request->slug, "-");
        $tag->meta_title = $request->meta_title;
        $tag->meta_desscription = $request->meta_desscription;
        $tag->meta_keywords = $request->meta_keywords;
        $tag->save();

        Session::flash('success', 'The new tag was successfully created!');

        // redirect to another page
        
        return redirect()->route('tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $tag = Tag::where('slug', '=', $slug)->first();

        $tags = Tag::all();

        $posts = $tag->posts()->orderBy('post_tag.post_id', 'desc')->paginate(5);

        $populars = Post::orderBy('id', 'desc')->where('popular_post', '=', '1')->limit(3)->get();

       // return view('tags.show')->withTag($tag)->withPosts($posts);

        if ($request->ajax()) {
            //return view('bars.load', ['items' => $items])->render();  
            return view('tags.load', ['posts' => $posts])->render(); 
        }
        // Return a view and pass in the above variable
        return view('tags.show')->withTag($tag)->withPosts($posts)->withTags($tags)->withPopulars($populars);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);

        // Return the view and pass in the variable we previously created

        return view('tags.edit')->withTag($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);

        // Validate the data 
        $this->validate($request, array(
            'name'  => 'required|max:255',
            'slug'         => "required|alpha_dash|min:5|max:255|unique:tags,slug,$id",
            'meta_title'     => 'required|max:70',
            'meta_desscription' => 'required|max:160',
            'meta_keywords'  => 'required'
        ));

        // store in the database

        $tag->name = $request->input('name');
        $tag->slug = str_slug($request->input('slug'), "-");
        $tag->meta_title = $request->input('meta_title');
        $tag->meta_desscription = $request->input('meta_desscription');
        $tag->meta_keywords = $request->input('meta_keywords');
        $tag->save();

        // redirect to another page with flash message

        Session::flash('success', 'The tag was successfully updated!');
        
        return redirect()->route('tags.index');
    }

    public function delete($id)
    {
        $tag = Tag::find($id);

        return view('tags.delete')->withTag($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);

        $tag->posts()->detach();

        $tag->delete();

        Session::flash('success', 'The tag was successfully deleted!');

        return redirect()->route('tags.index');
    }
}
