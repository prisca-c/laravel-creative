<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(): \Illuminate\Support\Collection
    {
        return DB::table('users')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function show($id): \Illuminate\Support\Collection
    {
        return DB::table('users')
        ->where('id', $id)
        ->get();
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required', 'max:255',
            'email' => 'required', 'max:255', 'unique:users,email',
            'password' => 'required', 'max:255',
            'role_id' => 'required', 'exists:roles,id'
        ]);

        if ($validated) {
            $this->user->name = $request->input('name');
            $this->user->email = $request->input('email');
            $this->user->password = $request->input('password');
            $this->user->save();

            return redirect()->back()->with('success', 'User created successfully');
        } else {
            return redirect()->back()->withErrors($validated);
        }
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required', 'max:255',
            'email' => 'required', 'max:255',
            'password' => 'required', 'max:255',
            'role_id' => 'required', 'exists:roles,id'
        ]);

        if ($validated) {
            $this->user->name = $request->input('name');
            $this->user->email = $request->input('email');
            $this->user->password = $request->input('password');
            $this->user->save();

            return redirect()->back()->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->withErrors($validated);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
