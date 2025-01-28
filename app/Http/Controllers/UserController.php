<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkZone;
use App\Utils\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function dashboard()
    {
        $workZones = WorkZone::with('work_zone_status')->get();
        return inertia('User/Dashboard')->with([
            'workZones' => $workZones
        ]);
    }

    public function map()
    {
        $workZones = WorkZone::all();
        $google_maps_api_key = config('services.google_maps.api_key');
        return inertia('WorkZones/Map')->with([
            'workZones' => $workZones,
            'google_maps_api_key' => $google_maps_api_key,
        ]);
    }

    public function showProfile()
    {
        return inertia('User/Profile/Show');
    }

    public function editProfile()
    {
        $user = Auth::user();
        return inertia('User/Profile/Edit')->with('user', $user);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|max:10240',
        ]);
        $user = User::findOrFail(Auth::id());
        if ($request->hasFile('image')) {
            $image_name_to_save = ImageUploader::uploadUserImage($request->file('image'));
            Storage::delete('public/user/image/' . $user->image);
            $user->image = $image_name_to_save;
        }
        $user->name = $request->input('name');
        $user->update();
        return redirect(route('user.profile.show'));
    }
}
