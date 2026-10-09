<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">Master Menu Makanan</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola data makanan, minuman, dan cemilan restoran</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold uppercase tracking-wider rounded-lg transition border border-gray-300">
                    Rekap Pesanan
                </a>
                <a href="{{ route('foods.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold uppercase tracking-wider rounded-lg shadow-sm transition">
                    + Tambah Menu Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-3.5 px-4 text-center">Foto</th>
                            <th class="py-3.5 px-4">Nama Menu</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Harga Satuan</th>
                            <th class="py-3.5 px-4">Deskripsi</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($foods as $food)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3 px-4 text-center">
                                    @if($food->image)
                                        <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-14 h-14 object-cover rounded-lg border border-gray-200 mx-auto">
                                    @else
                                        <div class="w-14 h-14 rounded-lg bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center text-[10px] text-gray-400 font-medium mx-auto">
                                            No Foto
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-semibold text-gray-900">{{ $food->name }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-md {{ $food->category == 'Makanan' ? 'bg-amber-100 text-amber-800' : ($food->category == 'Minuman' ? 'bg-sky-100 text-sky-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ $food->category }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-bold text-emerald-700 whitespace-nowrap">
                                    Rp {{ number_format($food->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-gray-500 text-xs max-w-xs truncate">
                                    {{ $food->description }}
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('foods.edit', $food->id) }}" class="px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition border border-gray-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('foods.destroy', $food->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu {{ $food->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg transition border border-rose-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-gray-400">
                                    Belum ada data menu makanan. Silakan tambahkan menu pertama Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($foods->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $foods->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
