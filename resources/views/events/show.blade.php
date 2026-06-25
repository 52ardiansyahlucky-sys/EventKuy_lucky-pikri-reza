<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $event->name }}</h2>
            <a href="{{ route('events.edit', $event) }}" class="text-sm text-yellow-600 hover:underline">Edit Detail Event</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-md">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-md">{{ session('error') }}</div>
            @endif

            {{-- Detail Event --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Detail Event</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Tanggal</dt>
                        <dd class="font-medium">{{ $event->event_date->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd class="font-medium">{{ ucfirst($event->status) }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Deskripsi</dt>
                        <dd>{{ $event->description ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Anggaran Total</dt>
                        <dd class="font-medium">Rp {{ number_format($event->total_budget, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Anggaran Terpakai</dt>
                        <dd class="font-medium">Rp {{ number_format($event->used_budget, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Venue Event --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Venue / Lokasi</h3>

                @if ($event->venues->isEmpty())
                    <p class="text-gray-500 text-sm mb-4">Belum ada venue yang dipilih untuk event ini.</p>
                @else
                    <ul class="divide-y text-sm mb-4">
                        @foreach ($event->venues as $venue)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('venues.show', $venue) }}" class="text-indigo-600 hover:underline font-medium">{{ $venue->name }}</a>
                                    <span class="text-gray-500"> &middot; {{ $venue->city }}</span>
                                    @if ($venue->hasCoordinates())
                                        <span class="text-gray-400 text-xs"> ({{ $venue->latitude }}, {{ $venue->longitude }})</span>
                                    @endif
                                    @if ($venue->pivot->notes)
                                        <p class="text-gray-500 text-xs mt-1">{{ $venue->pivot->notes }}</p>
                                    @endif
                                </div>
                                <form action="{{ route('event-venues.destroy', [$event, $venue]) }}" method="POST" onsubmit="return confirm('Lepas venue ini dari event?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Lepas</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                {{-- Form tambah venue ke event --}}
                <form action="{{ route('event-venues.store', $event) }}" method="POST" class="grid grid-cols-3 gap-2 items-end">
                    @csrf
                    <div class="col-span-2">
                        <label class="block text-xs text-gray-500">Pilih Venue</label>
                        <select name="venue_id" required class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">-- Pilih Venue --</option>
                            @foreach ($availableVenues as $venue)
                                <option value="{{ $venue->id }}">{{ $venue->name }} ({{ $venue->city }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm h-fit">+ Tambah Venue</button>
                </form>
                @if ($availableVenues->isEmpty())
                    <p class="text-xs text-gray-400 mt-2">
                        Belum ada data venue. <a href="{{ route('venues.create') }}" class="text-indigo-600 hover:underline">Tambah venue baru</a>.
                    </p>
                @endif
            </div>

            {{-- Laporan Kesiapan Cuaca --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Laporan Kesiapan Cuaca</h3>
                    <form action="{{ route('weather.refresh', $event) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-sky-600 text-white rounded-md text-sm hover:bg-sky-700">
                            🔄 Cek Cuaca Sekarang
                        </button>
                    </form>
                </div>

                @if ($event->weatherReports->isEmpty())
                    <p class="text-gray-500 text-sm">
                        Belum ada laporan cuaca. Klik "Cek Cuaca Sekarang" untuk mengambil data terbaru
                        (pastikan event ini sudah memiliki venue dengan koordinat lat/long terisi).
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($event->weatherReports as $report)
                            <div class="border rounded-md p-4
                                @class([
                                    'border-green-300 bg-green-50' => $report->recommendation_level === 'aman',
                                    'border-yellow-300 bg-yellow-50' => $report->recommendation_level === 'waspada',
                                    'border-red-300 bg-red-50' => $report->recommendation_level === 'siaga',
                                ])">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="font-medium text-sm">{{ $report->venue->name }} &middot; {{ $report->venue->city }}</p>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @class([
                                            'bg-green-200 text-green-800' => $report->recommendation_level === 'aman',
                                            'bg-yellow-200 text-yellow-800' => $report->recommendation_level === 'waspada',
                                            'bg-red-200 text-red-800' => $report->recommendation_level === 'siaga',
                                        ])">
                                        {{ ucfirst($report->recommendation_level) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-4 gap-2 text-xs text-gray-600 mb-2">
                                    <div>🌡️ {{ $report->temperature }}°C</div>
                                    <div>💧 Hujan: {{ $report->rain_probability }}%</div>
                                    <div>💨 Angin: {{ $report->wind_speed }} m/s</div>
                                    <div>☁️ {{ ucfirst($report->weather_description) }}</div>
                                </div>

                                <p class="text-sm text-gray-700">{{ $report->recommendation_text }}</p>

                                <p class="text-xs text-gray-400 mt-2">
                                    Terakhir dicek: {{ $report->checked_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Rundown Acara --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Rundown Acara</h3>

                <table class="w-full text-sm text-left mb-4">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2">Waktu</th>
                            <th class="px-3 py-2">Kegiatan</th>
                            <th class="px-3 py-2">PIC</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($event->rundowns as $item)
                            <tr>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</td>
                                <td class="px-3 py-2">{{ $item->activity }}</td>
                                <td class="px-3 py-2">{{ $item->person_in_charge ?: '-' }}</td>
                                <td class="px-3 py-2">
                                    <form action="{{ route('rundowns.destroy', [$event, $item]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item rundown ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-3 text-gray-500">Belum ada rundown.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Form tambah rundown --}}
                <form action="{{ route('rundowns.store', $event) }}" method="POST" class="grid grid-cols-5 gap-2 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-500">Mulai</label>
                        <input type="time" name="start_time" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Selesai</label>
                        <input type="time" name="end_time" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Kegiatan</label>
                        <input type="text" name="activity" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">PIC</label>
                        <input type="text" name="person_in_charge" class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm h-fit">+ Tambah</button>
                </form>
                @error('end_time') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Anggaran --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Rincian Anggaran</h3>

                <table class="w-full text-sm text-left mb-4">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2">Item</th>
                            <th class="px-3 py-2">Kategori</th>
                            <th class="px-3 py-2">Qty</th>
                            <th class="px-3 py-2">Harga Satuan</th>
                            <th class="px-3 py-2">Subtotal</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($event->budgets as $budget)
                            <tr>
                                <td class="px-3 py-2">{{ $budget->item_name }}</td>
                                <td class="px-3 py-2">{{ $budget->category ?: '-' }}</td>
                                <td class="px-3 py-2">{{ $budget->quantity }}</td>
                                <td class="px-3 py-2">Rp {{ number_format($budget->unit_price, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 font-medium">Rp {{ number_format($budget->subtotal, 0, ',', '.') }}</td>
                                <td class="px-3 py-2">
                                    <form action="{{ route('budgets.destroy', [$event, $budget]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item anggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-3 py-3 text-gray-500">Belum ada item anggaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Form tambah anggaran --}}
                <form action="{{ route('budgets.store', $event) }}" method="POST" class="grid grid-cols-5 gap-2 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-500">Nama Item</label>
                        <input type="text" name="item_name" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Kategori</label>
                        <input type="text" name="category" class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Qty</label>
                        <input type="number" name="quantity" min="1" value="1" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Harga Satuan</label>
                        <input type="number" step="0.01" name="unit_price" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm h-fit">+ Tambah</button>
                </form>
            </div>

            <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Kembali ke daftar event</a>
        </div>
    </div>
</x-app-layout>
