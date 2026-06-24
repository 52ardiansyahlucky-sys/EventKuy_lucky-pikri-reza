<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::where('user_id', Auth::id());

        // Fitur cari sederhana (boleh dipakai juga oleh Mhs 1 saat memilih venue)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
        }

        $venues = $query->latest()->paginate(10);

        return view('venues.index', compact('venues'));
    }

    public function create()
    {
        return view('venues.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateVenue($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('venues', 'public');
        }

        $validated['user_id'] = Auth::id();

        $venue = Venue::create($validated);

        return redirect()
            ->route('venues.show', $venue)
            ->with('success', 'Venue berhasil ditambahkan.');
    }

    public function show(Venue $venue)
    {
        $this->authorizeOwner($venue);

        $venue->load('events');

        return view('venues.show', compact('venue'));
    }

    public function edit(Venue $venue)
    {
        $this->authorizeOwner($venue);

        return view('venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        $this->authorizeOwner($venue);

        $validated = $this->validateVenue($request);

        if ($request->hasFile('photo')) {
            // Hapus foto lama supaya storage tidak penuh sampah
            if ($venue->photo) {
                Storage::disk('public')->delete($venue->photo);
            }
            $validated['photo'] = $request->file('photo')->store('venues', 'public');
        }

        $venue->update($validated);

        return redirect()
            ->route('venues.show', $venue)
            ->with('success', 'Venue berhasil diperbarui.');
    }

    public function destroy(Venue $venue)
    {
        $this->authorizeOwner($venue);

        if ($venue->photo) {
            Storage::disk('public')->delete($venue->photo);
        }

        $venue->delete();

        return redirect()
            ->route('venues.index')
            ->with('success', 'Venue berhasil dihapus.');
    }

    private function validateVenue(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:hall,ballroom,lapangan_outdoor,ruang_meeting,lainnya'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'capacity' => ['required', 'integer', 'min:0'],
            'rental_price' => ['required', 'numeric', 'min:0'],
            'facilities' => ['nullable', 'string'],
            'status' => ['required', 'in:available,booked,maintenance'],
            'photo' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);
    }

    private function authorizeOwner(Venue $venue): void
    {
        if ($venue->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke venue ini.');
        }
    }
}
