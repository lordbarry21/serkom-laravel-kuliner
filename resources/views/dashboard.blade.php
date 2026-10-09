<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Monitoring Pesanan Masuk (Kasir)
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Rekapitulasi transaksi pemesanan meja secara real-time</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('foods.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold uppercase tracking-wider rounded-lg shadow-sm transition">
                    Kelola Master Menu
                </a>
                <a href="{{ route('customer.index') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold uppercase tracking-wider rounded-lg transition border border-gray-300">
                    Buka Menu Meja &nearr;
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-base text-gray-900">Daftar Transaksi Terkini</h3>
                <span class="text-xs text-gray-500 font-medium">Total: {{ $orders->count() }} Transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-3.5 px-4 text-center"># ID</th>
                            <th class="py-3.5 px-4">Waktu</th>
                            <th class="py-3.5 px-4">Nama Pelanggan</th>
                            <th class="py-3.5 px-4">No. Meja</th>
                            <th class="py-3.5 px-4">Rincian Menu Yang Dipesan</th>
                            <th class="py-3.5 px-4">Total Bayar</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-center">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-4 px-4 font-bold text-gray-800 text-center">
                                    #{{ $order->id }}
                                </td>
                                <td class="py-4 px-4 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $order->created_at ? $order->created_at->format('d M H:i') : '-' }}
                                </td>
                                <td class="py-4 px-4 font-semibold text-gray-900">
                                    {{ $order->customer_name }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $order->table_number }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <ul class="space-y-1 text-xs text-gray-600">
                                        @foreach($order->orderDetails as $detail)
                                            <li class="flex items-center gap-1.5">
                                                <span class="font-bold text-gray-900">{{ $detail->food->name ?? 'Menu Terhapus' }}</span>
                                                <span class="text-gray-400">&times;</span>
                                                <span class="font-semibold text-gray-700">{{ $detail->quantity }}</span>
                                                <span class="text-gray-400">(&commat; Rp {{ number_format($detail->subtotal, 0, ',', '.') }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="py-4 px-4 font-extrabold text-emerald-700 whitespace-nowrap">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($order->status == 'Pending')
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-amber-100 text-amber-800 border border-amber-200">
                                            Pending
                                        </span>
                                    @elseif($order->status == 'Diproses')
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-sky-100 text-sky-800 border border-sky-200">
                                            Diproses
                                        </span>
                                    @elseif($order->status == 'Selesai')
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md bg-rose-100 text-rose-800 border border-rose-200">
                                            {{ $order->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs font-semibold py-1.5 px-2.5 rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900 bg-white shadow-xs cursor-pointer">
                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Diproses" {{ $order->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="Batal" {{ $order->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 px-4 text-center text-gray-400">
                                    Belum ada transaksi pesanan masuk dari pelanggan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
