<?php

namespace App\Http\Controllers;

use App\Models\SystemStatus;
use App\Models\WorkZone;
use App\Models\WorkZoneStatus;
use App\Utils\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class WorkZonesController extends Controller
{
    public function index()
    {
        $work_zones = WorkZone::with('work_zone_status')->get();
        return inertia('WorkZones/Index')->with([
            'work_zones' => $work_zones,
            'can' => [
                'create_work_zone' => Gate::allows('create', WorkZone::class),
            ],
        ]);
    }

    public function create()
    {
        Gate::authorize('create', WorkZone::class);

        $work_zone_statuses = WorkZoneStatus::all();
        $system_statuses = SystemStatus::all();
        return inertia('WorkZones/Create')->with([
            'work_zone_statuses' => $work_zone_statuses,
            'system_statuses' => $system_statuses,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', WorkZone::class);

        $validated = $request->validate([
            'work_zone_id' => 'string|required|unique:work_zones,work_zone_id',
            'name' => 'string|required',
            'location' => 'string|required',
            'latitude' => 'numeric|required',
            'longitude' => 'numeric|required',
            'start_date' => 'date|required',
            'work_zone_status_id' => 'integer|required',
            'image' => 'nullable|image|max:10240',
            'description' => 'nullable',
            'system_start_date' => 'date|nullable',
            'system_id' => 'string|nullable|unique:work_zones,system_id',
            'system_status_id' => 'integer|nullable',
        ]);

        $work_zone = new WorkZone($validated);
        $work_zone->user_id = Auth::id();
        if ($request->hasFile('image')) {
            $image_name_to_save = ImageUploader::uploadWorkZoneImage($request->file('image'));
            $work_zone->image = $image_name_to_save['image'];
            $work_zone->thumbnail = $image_name_to_save['thumbnail'];
        }
        $work_zone->save();

        return redirect(route('user.work_zones.index'));
    }

    public function show($id)
    {
        $work_zone = WorkZone::with(['work_zone_status', 'system_status'])->findOrFail($id);
        return inertia('WorkZones/Show')->with([
            'work_zone' => $work_zone,
            'can' => [
                'delete_work_zone' => Gate::allows('delete', $work_zone),
            ],
        ]);
    }

    public function dashboard($id)
    {
        $work_zone = WorkZone::with('work_zone_status')->findOrFail($id);
        $cord = [$work_zone->longitude, $work_zone->latitude];
        $google_maps_api_key = config('services.google_maps.api_key');
        return inertia('WorkZones/WorkZone/Dashboard')->with([
            'work_zone' => $work_zone,
            'cord' => $cord,
            'google_maps_api_key' => $google_maps_api_key,
        ]);
    }

    public function map($id)
    {
        $work_zone = WorkZone::findOrFail($id);
        $google_maps_api_key = config('services.google_maps.api_key');
        return inertia('WorkZones/WorkZone/Map')->with([
            'work_zone' => $work_zone,
            'google_maps_api_key' => $google_maps_api_key,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);

        // Check if the provided email matches the authenticated user's email
        if ($request->email === Auth::user()->email) {
            // Find the work zone by ID, or fail if not found
            $work_zone = WorkZone::findOrFail($id);

            Gate::authorize('delete', $work_zone);

            // Delete associated images and thumbnails if they exist
            if ($work_zone->image) {
                Storage::delete('public/work_zone/image/' . $work_zone->image);
            }
            if ($work_zone->thumbnail) {
                Storage::delete('public/work_zone/thumbnail/' . $work_zone->thumbnail);
            }

            // Delete the work zone record
            $work_zone->delete();

            return redirect()->route('user.work_zones.index')->with('success', 'Work zone deleted successfully');
        } else {
            // If the email doesn't match, redirect back with an error message
            return back()->with('error', 'Work zone could not be deleted, the email entered does not match your email.');
        }
    }

    public function test($id)
    {
        $work_zone = WorkZone::with('work_zone_status')->findOrFail($id);
        $cord = [$work_zone->longitude, $work_zone->latitude];
        $google_maps_api_key = config('services.google_maps.api_key');
        return inertia('WorkZones/WorkZone/Test')->with([
            'work_zone' => $work_zone,
            'cord' => $cord,
            'google_maps_api_key' => $google_maps_api_key,
        ]);
    }
}
