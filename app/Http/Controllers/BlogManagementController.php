<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blogs::all();
        return view('blogs.index', compact('blogs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'author' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('blog_images', 'public');

        $blog = new Blogs($validatedData);
        $blog->image = $imagePath;
        $blog->save();

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blogs::findOrFail($id);
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'author' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $blog = Blogs::findOrFail($id);


        $blog->name = $validatedData['name'];
        $blog->date = $validatedData['date'];
        $blog->author = $validatedData['author'];
        $blog->content = $validatedData['content'];


        if ($request->hasFile('image')) {

            Storage::disk('public')->delete($blog->image);
            $imagePath = $request->file('image')->store('blog_images', 'public');
            $blog->image = $imagePath;
        }

        $blog->save();

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blogs::find($id);

        if ($blog) {
            $blog->delete();
            return response()->json(['message' => 'Blog deleted successfully']);
        } else {
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $blogs = Blogs::where('name', 'like', '%' . $search . '%')->get();
        return view('blogs.search-results', compact('blogs'));
    }
}
