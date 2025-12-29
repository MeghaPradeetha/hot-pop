<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Mail\NotifyMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Http\Requests\LoginRequest;


class ManageUsersController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        return view('manage.users.index', [
            'pageTitle' => 'Manage Users',
            'users' => $users,
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('manage.users.edit', [
            'pageTitle' => 'Edit User',
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'age'               => 'nullable|integer',
            'location'          => 'nullable|string',
            'gender'            => 'nullable|string',
        ]);

        $user->update($validatedData);

        return redirect()->route('manage.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('manage.users.index')->with('success', 'User deleted successfully.');
    }

    // Custom Login for Admin matching previous logic but simplified
    public function logins(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->hasRole('super-admins') || $user->hasRole('admins') || $user->hasRole('developers')) {
                 return redirect()->intended('/manage/dashboard');
            } else {
                 return redirect('/logged-in');
            }
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
