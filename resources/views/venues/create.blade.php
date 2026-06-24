<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Venue Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('venues.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Venue</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Venue</label>
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="hall" {{ old('type') == 'hall' ? 'selected' : '' }}>Hall / Gedung</option>
                            <option value="ballroom" {{ old('type') == 'ballroom' ? 'selected' : '' }}>Ballroom Hotel</option>
                            <option value="lapangan_outdoor" {{ old('type') == 'lapangan_outdoor' ? 'selected' : '' }}>Lapangan Outdoor</option>
                            <option value="ruang_meeting" {{ old('type') == 'ruang_meeting' ? 'selected' : '' }}>Ruang Meeting</option>
                            <option value="lainnya" {{ old('type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kota</label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="misal: Jakarta"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="-6.200000"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('latitude') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="106.816666"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('longitude') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 -mt-2">
                        Tips: cari koordinat venue lewat Google Maps (klik kanan lokasi &rarr; klik koordinat untuk copy).
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kapasitas (orang)</label>
                            <input type="number" name="capacity" value="{{ old('capacity', 0) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('capacity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Sewa (Rp)</label>
                            <input type="number" step="0.01" name="rental_price" value="{{ old('rental_price', 0) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('rental_price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <textarea name="facilities" rows="2" placeholder="misal: AC, Sound System, Parkir luas, Proyektor"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('facilities') }}</textarea>
                        @error('facilities') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Venue</label>
                        <input type="file" name="photo" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-600">
                        <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB.</p>
                        @error('photo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('venues.index') }}" class="px-4 py-2 text-gray-600">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
