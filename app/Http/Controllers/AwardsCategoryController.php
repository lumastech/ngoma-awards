<?php

namespace App\Http\Controllers;

use App\Models\AwardsCategory;
use Illuminate\Http\Request;

class AwardsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // awards category with pagination 15
        $awardsCategories = AwardsCategory::paginate(15);
        // return inertia view
        return Inertia::render('AwardsCategory/index', [
            'awardsCategories' => $awardsCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //  return inertia view
        return Inertia::render('AwardsCategory/create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'award_id' => 'required',
        ]);

        // create awards category
        $awardsCategory = AwardsCategory::create([
            'name' => $request->name,
            'award_id' => $request->award_id,
        ]);

        // return inertia view
        return redirect()->route('awards-categories.index')->with('success', 'Awards Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AwardsCategory $awardsCategory)
    {
        // return inertia view
        return Inertia::render('AwardsCategory/show', [
            'awardsCategory' => $awardsCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AwardsCategory $awardsCategory)
    {
        // return inertia view
        return Inertia::render('AwardsCategory/edit', [
            'awardsCategory' => $awardsCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AwardsCategory $awardsCategory)
    {
        // validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'award_id' => 'required',
        ]);

        // update awards category
        $awardsCategory->update([
            'name' => $request->name,
            'award_id' => $request->award_id,
        ]);

        // return inertia view
        return redirect()->route('awards-categories.index')->with('success', 'Awards Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AwardsCategory $awardsCategory)
    {
        // delete awards category
        $awardsCategory->delete();
        // return inertia view
        return redirect()->route('awards-categories.index')->with('success', 'Awards Category deleted successfully.');
    }
}
