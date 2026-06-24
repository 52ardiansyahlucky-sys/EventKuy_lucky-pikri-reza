<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventVenueController extends Controller
{
    // Tambah venue ke event
    public function store(Request $request, Event $event)
    {
        $this->authorizeEventOwner($event);

        $validated = $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'notes' => ['nullable', 'string'],
        ]);

        // syncWithoutDetaching supaya venue lama yang sudah ada tidak terhapus
        $event->venues()->syncWithoutDetaching([
            $validated['venue_id'] => ['notes' => $validated['notes'] ?? null],
        ]);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Venue berhasil ditambahkan ke event.');
    }

    // Lepas venue dari event
    public function destroy(Event $event, Venue $venue)
    {
        $this->authorizeEventOwner($event);

        $event->venues()->detach($venue->id);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Venue berhasil dilepas dari event.');
    }

    private function authorizeEventOwner(Event $event): void
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
