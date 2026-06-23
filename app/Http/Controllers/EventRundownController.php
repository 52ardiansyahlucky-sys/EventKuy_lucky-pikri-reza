<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRundown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRundownController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorizeOwner($event);

        $validated = $request->validate([
            'activity' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'person_in_charge' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['event_id'] = $event->id;
        $validated['order'] = $event->rundowns()->count() + 1;

        EventRundown::create($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Item rundown berhasil ditambahkan.');
    }

    public function update(Request $request, Event $event, EventRundown $rundown)
    {
        $this->authorizeOwner($event);

        $validated = $request->validate([
            'activity' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'person_in_charge' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $rundown->update($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Item rundown berhasil diperbarui.');
    }

    public function destroy(Event $event, EventRundown $rundown)
    {
        $this->authorizeOwner($event);

        $rundown->delete();

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Item rundown berhasil dihapus.');
    }

    private function authorizeOwner(Event $event): void
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
