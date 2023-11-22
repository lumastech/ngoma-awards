<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // user with pagination 15
        $users = User::paginate(15);
        // return inertia view
        return Inertia::render('User/index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return inertia view
        return Inertia::render('User/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string',
        ]);

        // random string password
        $password = Str::random(8);


        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'password' => bcrypt($password),
            'address' => $request->address,
            'status' => $request->status,
        ]);

        // send email with password to user
        // Mail::to($user->email)->send(new UserCreated($user, $password));

        // return inertia view
        return redirect()->back()->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find user
        $user = User::find($id);
        // return inertia view
        return Inertia::render('User/show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // find user
        $user = User::find($id);
        // return inertia view
        return Inertia::render('User/edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|string',
        ]);

        // find user
        $user = User::find($id);
         // update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        // return inertia view
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // find user
        $user = User::find($id);
        // delete user
        $user->delete();
        // return inertia view
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // search users
    public function search(Request $request)
    {
        // validate request
        $request->validate([
            'search' => 'nullable|string',
        ]);

        // search is empty then return user with pagination 20
        if($request->search == ''){
            $users = User::paginate(15);
        }

        // filter users
        $users = User::where('name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('email', 'LIKE', '%' . $request->search . '%')
            ->orWhere('role', 'LIKE', '%' . $request->search . '%')
            ->orWhere('phone', 'LIKE', '%' . $request->search . '%')
            ->orWhere('address', 'LIKE', '%' . $request->search . '%')
            ->orWhere('status', 'LIKE', '%' . $request->search . '%')
            ->paginate(15);

        // return users
        return \response()->json($users);


    }
}
