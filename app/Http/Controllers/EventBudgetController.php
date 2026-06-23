<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventBudgetController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorizeOwner($event);

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['event_id'] = $event->id;
        $validated['subtotal'] = $validated['quantity'] * $validated['unit_price'];

        EventBudget::create($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Item anggaran berhasil ditambahkan.');
    }

    public function update(Request $request, Event $event, EventBudget $budget)
    {
        $this->authorizeOwner($event);

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['subtotal'] = $validated['quantity'] * $validated['unit_price'];

        $budget->update($validated);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Item anggaran berhasil diperbarui.');
    }

    public function destroy(Event $event, EventBudget $budget)
    {
        $this->authorizeOwner($event);

        $budget->delete();

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Item anggaran berhasil dihapus.');
    }

    private function authorizeOwner(Event $event): void
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
