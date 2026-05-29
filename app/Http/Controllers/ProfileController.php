<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show edit profile page
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email,' . $user->id,

            // password optional
            'current_password' => 'nullable|required_with:password',

            'password' => [
                'nullable',
                'confirmed',
                Password::min(6),
            ],
        ]);

        // Update normal fields
        $user->name = $data['name'];
        $user->email = $data['email'];

        // User wants to change password
        if (!empty($data['password'])) {

            // Verify current password
            if (!Hash::check($data['current_password'], $user->password)) {

                return back()->withErrors([
                    'current_password' => 'Current password is incorrect.',
                ]);
            }

            // Auto hashed by model cast
            $user->password = $data['password'];
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
    /**
     * Delete account
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Optional but smart: confirm password before deletion
        $request->validate([
            'password' => ['required'],
        ]);

        // Check password
        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password.',
            ]);
        }

        Auth::logout();

        $user->delete();

        return redirect('/')
            ->with('success', 'Account deleted successfully.');
    }
}
