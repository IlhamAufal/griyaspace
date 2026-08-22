<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(10);
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
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Room $room)
    {
        $recentBookings = $room->bookings()->with('organization')->latest()->take(5)->get();
        return view('pages.rooms.show', compact('room', 'recentBookings'));
    }

    public function edit(Room $room)
    {
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
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $room->update(['status' => 'inactive']);
        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dinonaktifkan.');
    }
}
