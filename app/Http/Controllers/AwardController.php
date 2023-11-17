<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awards = Award::paginate(10);

        return render('awards.index', compact('awards'));
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
        // validate the request...
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $award = Award::create([
            'name' => $request->name,
        ]);

        return redirect()->route('awards.index')->with('success', 'Award created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Award $award)
    {
        //  return inertia view
        return Inertia::render('Award/show', [
            'award' => $award,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Award $award)
    {
        // return inertia view
        return Inertia::render('Award/edit', [
            'award' => $award,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Award $award)
    {
        // validate request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // update award
        $award->update([
            'name' => $request->name,
        ]);

        // return inertia view
        return redirect()->route('awards.index')->with('success', 'Award updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Award $award)
    {
        // delete award
        $award->delete();
    }
}
