<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'total_budget' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,planned,completed'],
        ]);

        $validated['user_id'] = Auth::id();

        $event = Event::create($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Event berhasil dibuat.');
    }

    public function show(Event $event)
    {
        $this->authorizeOwner($event);

        $event->load(['rundowns', 'budgets']);

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $this->authorizeOwner($event);

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOwner($event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'total_budget' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,planned,completed'],
        ]);

        $event->update($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeOwner($event);

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Pastikan event ini milik user yang sedang login.
     * Sederhana, tanpa pakai Policy (cukup untuk skala tugas).
     */
    private function authorizeOwner(Event $event): void
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
