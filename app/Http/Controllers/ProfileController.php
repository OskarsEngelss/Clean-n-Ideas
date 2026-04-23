<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\TutorialMedia;

class ProfileController extends Controller
{
    /**
     * Atjaunina lietotāja profila informāciju, apstrādājot e-pasta maiņu, profila attēla augšupielādi un paroles atjaunošanu.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse {
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

            $request->validate([
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);

            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return Redirect::route('profile.settings')->with('status', 'profile-updated');
    }

    /**
     * Dzēš lietotāja kontu, pirms tam notīrot visus saistītos failus, pamācību medijus (experience media) un pārtraucot sesiju.
     */
    public function destroy(Request $request): RedirectResponse {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->profile_picture) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_picture);
        }

        $mediaPaths = TutorialMedia::where('user_id', $user->id)->pluck('path')->toArray();
        forEach($mediaPaths as $path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    /**
     * Parāda lietotāja publisko profilu ar tā jaunākajām publiskajām pamācībām (experiences).
     */
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
    /**
     * Papildus ielādē lietotāja publiskās pamācības (experiences) profila lapā, izmantojot bezgalīgo ritināšanu (infinite scroll).
     */
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


    /**
     * Parāda autorizētā lietotāja personīgo sekošanas (following) sarakstu. (Saraksts ar profiliem kuriem lietotājs seko)
     */
    public function followers() {
        $following = Auth::user()
                    ->following()
                    ->latest()
                    ->take(8)
                    ->get();

        return view('followers', compact('following'));
    }
    /**
     * Papildus ielādē profilus kuriem autorizētais lietotājs seko.
     */
    public function followersLoadMore(Request $request) {
        $page = (int) $request->get('page', 1);
        $perPage = 8;
        $skip = ($page - 1) * $perPage;

        $following = Auth::user()
                    ->following()
                    ->latest()
                    ->skip($skip) 
                    ->take($perPage)
                    ->get();

        if ($following->isEmpty()) {
            return response('', 200);
        }

        return view('partials._following', compact('following'));
    }

    /**
     * Pievieno vai noņem sekošanu (follow/unfollow) citam lietotājam.
     */
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

    /**
     * Atver lietotāja profila iestatījumu (settings) lapu.
     */
    public function settings() {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }
}
