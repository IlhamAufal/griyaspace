<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Room;
use App\Models\RoomPhoto;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('photos')->latest()->paginate(10);
        return view('pages.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('pages.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:rooms,code',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'open_time' => 'required',
            'close_time' => 'required|after:open_time',
            'status' => 'required|in:active,inactive',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $room = Room::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('rooms', 'public');
                $room->photos()->create([
                    'photo_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Room $room)
    {
        $room->load('photos');
        $recentBookings = $room->bookings()->with('organization')->latest()->take(5)->get();
        return view('pages.rooms.show', compact('room', 'recentBookings'));
    }

    public function edit(Room $room)
    {
        $room->load('photos');
        return view('pages.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:rooms,code,' . $room->id,
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'open_time' => 'required',
            'close_time' => 'required|after:open_time',
            'status' => 'required|in:active,inactive',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $room->update($validated);

        if ($request->hasFile('photos')) {
            $maxOrder = $room->photos()->max('sort_order') ?? -1;
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('rooms', 'public');
                $room->photos()->create([
                    'photo_path' => $path,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function storePhoto(Request $request, Room $room)
    {
        if ($room->photos()->count() >= 6) {
            return redirect()->route('rooms.edit', $room)->with('error', 'Maksimal 6 foto yang diperbolehkan untuk satu ruangan.');
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $maxOrder = $room->photos()->max('sort_order') ?? -1;
        $path = $request->file('photo')->store('rooms', 'public');
        $room->photos()->create([
            'photo_path' => $path,
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->route('rooms.edit', $room)->with('success', 'Foto berhasil ditambahkan.');
    }

    public function destroyPhoto(RoomPhoto $photo)
    {
        $room = $photo->room;
        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return redirect()->route('rooms.edit', $room)->with('success', 'Foto berhasil dihapus.');
    }

    public function destroy(Room $room)
    {
        $room->update(['status' => 'inactive']);
        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dinonaktifkan.');
    }
}
