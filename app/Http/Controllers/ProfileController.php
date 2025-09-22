<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(User $user) {
        return view('profile.show', ['user' => $user, 'followersCount' => $user->followers()->count()]);
    }

    public function followers() {
        $following = Auth::user()->following()->get();
        return view('followers', compact('following'));
    }

    public function toggleFollow(User $user) {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            abort(403, "You can't follow yourself.");
        }

        $isFollowing = $authUser->following()->where('followed_id', $user->id)->exists();

        if ($isFollowing) {
            $authUser->following()->detach($user->id);
        } else {
            $authUser->following()->attach($user->id);
        }

        return back();
    }
}
