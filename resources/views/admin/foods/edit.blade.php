<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Edit Menu Makanan</h2>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi katalog untuk {{ $food->name }}</p>
            </div>
            <a href="{{ route('foods.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold uppercase tracking-wider rounded-lg transition border border-gray-300">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
            <form action="{{ route('foods.update', $food->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Menu</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $food->name) }}" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori Menu</label>
                        <select id="category" name="category" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                            <option value="Makanan" {{ old('category', $food->category) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('category', $food->category) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Cemilan" {{ old('category', $food->category) == 'Cemilan' ? 'selected' : '' }}>Cemilan</option>
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1.5">Harga Satuan (Rp)</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $food->price) }}" min="0" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                        @error('price')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Menu</label>
                    <textarea id="description" name="description" rows="3" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">{{ old('description', $food->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Menu Saat Ini</label>
                    @if($food->image)
                        <div class="mb-3 flex items-center gap-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
                            <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-16 h-16 object-cover rounded-lg border">
                            <span class="text-xs text-gray-500">Foto aktif saat ini. Unggah file baru di bawah jika ingin mengganti.</span>
                        </div>
                    @else
                        <p class="text-xs text-gray-400 mb-3 italic">Menu ini belum memiliki foto.</p>
                    @endif

                    <label for="image" class="block text-xs font-semibold text-gray-600 mb-1">Unggah Foto Baru (Opsional)</label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-xl">
                    @error('image')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('foods.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        Perbarui Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
