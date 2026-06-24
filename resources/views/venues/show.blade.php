<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $venue->name }}</h2>
            <a href="{{ route('venues.edit', $venue) }}" class="text-sm text-yellow-600 hover:underline">Edit Venue</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-md">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <img src="{{ $venue->photo_url }}" alt="{{ $venue->name }}" class="w-full h-64 object-cover">
                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Tipe</dt>
                            <dd class="font-medium">{{ ucfirst(str_replace('_', ' ', $venue->type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Status</dt>
                            <dd class="font-medium">{{ ucfirst($venue->status) }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-gray-500">Alamat</dt>
                            <dd>{{ $venue->address }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Kota</dt>
                            <dd class="font-medium">{{ $venue->city }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Koordinat</dt>
                            <dd class="font-medium">
                                @if ($venue->hasCoordinates())
                                    {{ $venue->latitude }}, {{ $venue->longitude }}
                                @else
                                    <span class="text-gray-400">Belum diisi</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Kapasitas</dt>
                            <dd class="font-medium">{{ $venue->capacity }} orang</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Harga Sewa</dt>
                            <dd class="font-medium">Rp {{ number_format($venue->rental_price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-gray-500">Fasilitas</dt>
                            <dd>{{ $venue->facilities ?: '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Daftar event yang memakai venue ini --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Event yang Menggunakan Venue Ini</h3>

                @if ($venue->events->isEmpty())
                    <p class="text-gray-500 text-sm">Belum ada event yang menggunakan venue ini.</p>
                @else
                    <ul class="divide-y text-sm">
                        @foreach ($venue->events as $event)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:underline font-medium">{{ $event->name }}</a>
                                    <span class="text-gray-500"> &middot; {{ $event->event_date->format('d M Y') }}</span>
                                    @if ($event->pivot->notes)
                                        <p class="text-gray-500 text-xs mt-1">{{ $event->pivot->notes }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <a href="{{ route('venues.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Kembali ke daftar venue</a>
        </div>
    </div>
</x-app-layout>
