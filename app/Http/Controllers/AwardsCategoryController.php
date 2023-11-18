<?php

namespace App\Http\Controllers;

use App\Models\AwardsCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Award;

class AwardsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // awards category with pagination 15
        $categories = AwardsCategory::with('award')->with('artists')->paginate(15);
        $awards = Award::all();
        // return inertia view
        return Inertia::render('Category/index', [
            'categories' => $categories,
            'awards' => $awards,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //  return inertia view
        return Inertia::render('Category/create');

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
        return redirect()->back()->with('success', 'Awards Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AwardsCategory $awardsCategory)
    {
        // return inertia view
        return Inertia::render('Category/show', [
            'awardsCategory' => $awardsCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AwardsCategory $awardsCategory)
    {
        // return inertia view
        return Inertia::render('Category/edit', [
            'awardsCategory' => $awardsCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $awardsCategory)
    {
        // validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'award_id' => 'required',
        ]);

        $category = AwardsCategory::where('id', $awardsCategory)->first();
        if(!$category){
            return redirect()->back()->with('error', 'Awards Category not found.');
        }
        // update awards category
        $category->update([
            'name' => $request->name,
            'award_id' => $request->award_id,
        ]);

        // return inertia view
        return redirect()->back()->with('success', 'Awards Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($awardsCategory)
    {
        $category = AwardsCategory::where('id', $awardsCategory)->first();
        if(!$category){
            return redirect()->back()->with('error', 'Awards Category not found.');
        }
        // delete awards category
        $category->delete();
        // return inertia view
        return redirect()->back()->with('success', 'Awards Category deleted successfully.');
    }
}
