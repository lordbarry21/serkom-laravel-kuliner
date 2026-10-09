<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Tambah Menu Makanan</h2>
                <p class="text-sm text-gray-500 mt-0.5">Input item baru ke dalam master katalog menu</p>
            </div>
            <a href="{{ route('foods.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold uppercase tracking-wider rounded-lg transition border border-gray-300">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
            <form action="{{ route('foods.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Menu</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Nasi Goreng Spesial" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori Menu</label>
                        <select id="category" name="category" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                            <option value="Makanan" {{ old('category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Cemilan" {{ old('category') == 'Cemilan' ? 'selected' : '' }}>Cemilan</option>
                        </select>
                        @error('category')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1.5">Harga Satuan (Rp)</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" required placeholder="Contoh: 25000" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">
                        @error('price')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Menu</label>
                    <textarea id="description" name="description" rows="3" required placeholder="Jelaskan isi porsi, rasa, dan keunggulan menu..." class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Menu (Opsional)</label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded-xl">
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, atau WebP. Maksimal 2MB.</p>
                    @error('image')
                        <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('foods.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
