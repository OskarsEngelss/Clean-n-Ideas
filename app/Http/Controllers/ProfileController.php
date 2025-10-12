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
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');

            $user->profile_picture = $path;
        }

        if ($request->filled('password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Optional: validate new password rules manually
        $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();


        return Redirect::route('profile.settings')->with('status', 'profile-updated');
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
        $experiences = $user->experiences()
            ->where('visibility', 'Public')
            ->latest()
            ->take(9)
            ->get();

        return view('profile.show', [
            'user' => $user,
            'followersCount' => $user->followers()->count(),
            'experiences' => $experiences,
        ]);
    }

    public function loadMoreExperiences(User $user, Request $request) {
        $page = (int) $request->get('page', 1);
        $perPage = 9;
        $skip = ($page - 1) * $perPage;

        $experiences = $user->experiences()
            ->where('visibility', 'Public')
            ->latest()
            ->skip($skip)   
            ->take($perPage)
            ->get();

        if ($experiences->isEmpty()) {
            return response('', 200);
        }

        return view('partials._experience', compact('experiences'));
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

    public function settings() {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }
}
