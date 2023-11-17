<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artists = Artist::paginate(10);

        return render('artists.index', compact('artists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return render('artists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate the request...
        $request->validate([
            'name' => 'required|string|max:255',
            'awards_category_id' => 'required',
        ]);

        $artist = Artist::create([
            'name' => $request->name,
            'awards_category_id' => $request->awards_category_id,
        ]);

        return redirect()->route('artists.index')->with('success', 'Artist created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {

        if (!$artist) {
            return redirect()->route('artists.index')->with('error', 'Artist not found.');
        }
        // return inertia view
        return Inertia::render('Artist/show', [
            'artist' => $artist,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artist $artist)
    {
        if (!$artist) {
            return redirect()->route('artists.index')->with('error', 'Artist not found.');
        }
        // return inertia view
        return Inertia::render('Artist/edit', [
            'artist' => $artist,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist)
    {
        // validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'awards_category_id' => 'required',
        ]);

        // update artist
        $artist->update([
            'name' => $request->name,
            'awards_category_id' => $request->awards_category_id,
        ]);

        // return inertia view
        return redirect()->route('artists.index')->with('success', 'Artist updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        // delete artist
        $artist->delete();
    }
}
