<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Event 
            </h2>
            <!-- CTA dipindahkan ke hero banner -->
        </div>
    </x-slot>

    <!-- Hero background tema event (realistis) -->
    <div class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('/images/event-background.jpg'); filter: saturate(1.1) contrast(1.05);">
            </div>

            <!-- Light beams / stage glow -->
            <div class="absolute inset-0"
                 style="background:
                    radial-gradient(800px 400px at 50% 20%, rgba(255,255,255,0.22), transparent 55%),
                    radial-gradient(700px 350px at 20% 30%, rgba(99,102,241,0.28), transparent 60%),
                    radial-gradient(700px 350px at 80% 30%, rgba(168,85,247,0.24), transparent 60%);
                 mix-blend-mode: screen;">
            </div>

            <!-- Dark cinematic overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-indigo-900/45 to-indigo-900/10"></div>

            <!-- Subtle bokeh particles -->
            <div class="absolute inset-0"
                 style="background:
                    radial-gradient(circle at 20% 70%, rgba(255,255,255,0.14) 0 2px, transparent 3px),
                    radial-gradient(circle at 60% 40%, rgba(255,255,255,0.10) 0 2px, transparent 3px),
                    radial-gradient(circle at 80% 75%, rgba(255,255,255,0.12) 0 2px, transparent 3px),
                    radial-gradient(circle at 35% 20%, rgba(255,255,255,0.09) 0 2px, transparent 3px);
                 opacity: 0.55;">
            </div>
        </div>


        <div class="py-12 sm:py-16">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-2xl sm:text-3xl font-semibold text-white">
                            Daftar Event Saya
                        </h3>
                        <p class="mt-2 text-sm sm:text-base text-white/80">
                            Kelola event yang kamu buat—mulai dari draft, planned, sampai completed.
                        </p>
                    </div>

                    <div class="sm:text-right">
                        <a href="{{ route('events.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                            + Buat Event Baru
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($events->isEmpty())
                        <p class="text-gray-500">Belum ada event. Yuk buat event pertamamu!</p>
                    @else
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Nama Event</th>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Anggaran</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($events as $event)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $event->name }}</td>
                                        <td class="px-4 py-3">{{ $event->event_date->format('d M Y') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs
                                                @class([
                                                    'bg-gray-200 text-gray-700' => $event->status === 'draft',
                                                    'bg-blue-100 text-blue-700' => $event->status === 'planned',
                                                    'bg-green-100 text-green-700' => $event->status === 'completed',
                                                ])">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">Rp {{ number_format($event->total_budget, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 space-x-2">
                                            <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:underline">Lihat</a>
                                            <a href="{{ route('events.edit', $event) }}" class="text-yellow-600 hover:underline">Edit</a>
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


