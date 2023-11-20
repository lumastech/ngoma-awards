<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Award;
use App\Models\Artist;
use App\Models\Vote;
use App\Models\User;

class DashboardController extends Controller
{
    function dashboard() {
        $count_users = User::count();
        $count_artists = Artist::count();
        $count_awards = Award::count();
        $count_votes = Vote::count();
        $users = User::paginate(5);
        $artists = Artist::with('awardsCategory')->withCount('votes')->orderBy('votes_count', 'desc')->paginate(5);
        $awards = Award::with('categories')->paginate(5);

        return Inertia::render('Dashboard', compact('count_users', 'count_awards', 'count_votes', 'count_artists', 'users', 'artists', 'awards'));
    }

    // search dashboard
    function search(Request $request) {
        return 0;
    }
}
