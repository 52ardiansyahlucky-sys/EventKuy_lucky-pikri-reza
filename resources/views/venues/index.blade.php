<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Venue
            </h2>

        </div>
    </x-slot>

    <!-- Hero background (realistis & profesional) -->
    <div class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('/images/event-background.jpg');">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-indigo-900/45 to-indigo-900/10"></div>
        </div>

        <div class="py-12 sm:py-16">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-semibold text-white">
                            Daftar Venue
                        </h3>
                        <p class="mt-2 text-sm sm:text-base text-white/80">
                            Pilih venue yang sesuai untuk kebutuhan eventmu—hall, ballroom, outdoor, atau ruang meeting.
                        </p>
                    </div>
                    <div class="sm:text-right">
                        <a href="{{ route('venues.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                            + Tambah Venue
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form cari --}}
            <form method="GET" class="mb-6">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama venue atau kota..."
                       class="w-full sm:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </form>

            @if ($venues->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow-sm text-gray-500">
                    Belum ada venue. Yuk tambah venue pertamamu!
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($venues as $venue)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <img src="{{ $venue->photo_url }}" alt="{{ $venue->name }}" class="w-full h-40 object-cover">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="font-semibold text-gray-800">{{ $venue->name }}</h3>
                                    <span class="px-2 py-0.5 rounded-full text-xs
                                        @class([
                                            'bg-green-100 text-green-700' => $venue->status === 'available',
                                            'bg-yellow-100 text-yellow-700' => $venue->status === 'booked',
                                            'bg-gray-200 text-gray-700' => $venue->status === 'maintenance',
                                        ])">
                                        {{ ucfirst($venue->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-2">{{ $venue->city }} &middot; Kapasitas {{ $venue->capacity }} orang</p>
                                <p class="text-sm font-medium text-gray-700 mb-3">Rp {{ number_format($venue->rental_price, 0, ',', '.') }} / event</p>
                                <div class="flex justify-between text-sm">
                                    <a href="{{ route('venues.show', $venue) }}" class="text-indigo-600 hover:underline">Lihat Detail</a>
                                    <div class="space-x-2">
                                        <a href="{{ route('venues.edit', $venue) }}" class="text-yellow-600 hover:underline">Edit</a>
                                        <form action="{{ route('venues.destroy', $venue) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus venue ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $venues->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
