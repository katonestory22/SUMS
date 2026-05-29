<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.users.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'first_name' => 'required|string|max:255',

            'middle_name' => 'nullable|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'role' => 'required|in:admin,director,finance,technical',

            'status' => 'required|in:active,inactive',

            'phone' => 'nullable|string|max:20',

            'address' => 'nullable|string|max:255',

            'date_of_birth' => 'nullable|date',

            'national_id' => 'nullable|string|max:255',

            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $data['name'] =
            trim($data['first_name'] . ' ' . $data['last_name']);

        $data['password'] =
            Str::upper(trim($data['last_name']));

        if ($request->hasFile('passport_photo')) {

            $file = $request->file('passport_photo');

            $filename =
                time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/users'), $filename);

            $data['passport_photo'] =
                'uploads/users/' . $filename;
        }

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, User $user)
    {
        $data = $request->validate([

            'first_name' => 'required|string|max:255',

            'middle_name' => 'nullable|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' =>
                'required|email|unique:users,email,' . $user->id,

            'role' =>
                'required|in:admin,director,finance,technical',

            'status' =>
                'required|in:active,inactive',

            'phone' => 'nullable|string|max:20',

            'address' => 'nullable|string|max:255',

            'date_of_birth' => 'nullable|date',

            'national_id' => 'nullable|string|max:255',

            'password' => 'nullable|min:6|confirmed',

            'passport_photo' =>
                'nullable|image|mimes:jpg,jpeg,png|max:2048',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Password Update
        |--------------------------------------------------------------------------
        */

        if (empty($data['password'])) {

            unset($data['password']);
        }

        /*
        |--------------------------------------------------------------------------
        | Auto Name
        |--------------------------------------------------------------------------
        */

        $data['name'] =
            trim($data['first_name'] . ' ' . $data['last_name']);

        /*
        |--------------------------------------------------------------------------
        | Replace Photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('passport_photo')) {

            if (
                $user->passport_photo &&
                File::exists(public_path($user->passport_photo))
            ) {

                File::delete(public_path($user->passport_photo));
            }

            $file = $request->file('passport_photo');

            $filename =
                time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/users'), $filename);

            $data['passport_photo'] =
                'uploads/users/' . $filename;
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent Self Delete
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $user->id) {

            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Photo
        |--------------------------------------------------------------------------
        */

        if (
            $user->passport_photo &&
            File::exists(public_path($user->passport_photo))
        ) {

            File::delete(public_path($user->passport_photo));
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }


    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active'
                ? 'inactive'
                : 'active'
        ]);

        return back()->with(
            'success',
            'User status updated successfully.'
        );
    }
}
