<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PesanMakan - Menu Restoran & Pemesanan Mandiri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased pb-28 md:pb-12">

    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg tracking-wider">
                    PM
                </div>
                <div>
                    <h1 class="font-bold text-base text-slate-900 leading-tight">PesanMakan</h1>
                    <p class="text-xs text-slate-500 font-medium">Sistem Pemesanan Mandiri Meja</p>
                </div>
            </div>
            <div>
                <a href="{{ route('login') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition inline-flex items-center">
                    Login Admin
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8 flex-1 w-full">

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-sm font-medium flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-lg leading-none font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-900 text-sm font-medium flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 text-lg leading-none font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-900 text-sm font-medium">
                <p class="font-bold mb-1">Periksa kembali data Anda:</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf

            <!-- Section 1: Customer Data -->
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 mb-8 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="font-bold text-base text-slate-900">1. Data Pelanggan</h2>
                        <p class="text-xs text-slate-500">Masukkan identitas meja sebelum memilih makanan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="customer_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Nama Pemesan <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" id="customer_name" name="customer_name" required value="{{ old('customer_name') }}" placeholder="Contoh: Bari Achmad" class="w-full text-sm rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 py-2.5 px-3.5 border transition">
                    </div>
                    <div>
                        <label for="table_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Nomor Meja <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" id="table_number" name="table_number" required value="{{ old('table_number') }}" placeholder="Contoh: Meja 07" class="w-full text-sm rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900 py-2.5 px-3.5 border transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Category Filter & Menu Items -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h2 class="font-bold text-lg text-slate-900">2. Pilih Menu Favorit</h2>
                    <p class="text-xs text-slate-500">Gunakan filter atau tombol stepper untuk menambah porsi</p>
                </div>

                <!-- Category Filters -->
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="setCategoryFilter('all', this)" class="category-pill active-pill px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white transition">
                        Semua Menu
                    </button>
                    <button type="button" onclick="setCategoryFilter('Makanan', this)" class="category-pill px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-600 border border-slate-300 hover:bg-slate-100 transition">
                        Makanan
                    </button>
                    <button type="button" onclick="setCategoryFilter('Minuman', this)" class="category-pill px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-600 border border-slate-300 hover:bg-slate-100 transition">
                        Minuman
                    </button>
                    <button type="button" onclick="setCategoryFilter('Cemilan', this)" class="category-pill px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-600 border border-slate-300 hover:bg-slate-100 transition">
                        Cemilan
                    </button>
                </div>
            </div>

            <!-- Foods Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($foods as $food)
                    <div class="food-item bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs flex flex-col justify-between transition hover:border-slate-400" data-category="{{ $food->category }}">
                        <div>
                            @if($food->image)
                                <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" class="w-full h-44 object-cover bg-slate-100">
                            @else
                                <div class="w-full h-44 bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-400 border-b border-slate-100">
                                    Foto Menu Belum Tersedia
                                </div>
                            @endif

                            <div class="p-4 sm:p-5">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-md uppercase tracking-wider {{ $food->category == 'Makanan' ? 'bg-amber-100 text-amber-900' : ($food->category == 'Minuman' ? 'bg-sky-100 text-sky-900' : 'bg-purple-100 text-purple-900') }}">
                                        {{ $food->category }}
                                    </span>
                                    <span class="text-base font-bold text-slate-900">
                                        Rp {{ number_format($food->price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <h3 class="font-bold text-slate-900 text-base leading-snug">{{ $food->name }}</h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $food->description }}</p>
                            </div>
                        </div>

                        <!-- Stepper Controls -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500">Porsi Pesanan:</span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="adjustQty({{ $food->id }}, -1)" class="w-9 h-9 rounded-lg bg-white border border-slate-300 hover:bg-slate-100 text-slate-800 font-bold flex items-center justify-center transition active:scale-95">
                                    -
                                </button>
                                <input type="number" id="qty-{{ $food->id }}" name="items[{{ $food->id }}]" min="0" value="0" readonly data-id="{{ $food->id }}" data-name="{{ $food->name }}" data-price="{{ $food->price }}" class="item-input w-12 h-9 text-center font-bold text-slate-900 bg-white border border-slate-300 rounded-lg text-sm">
                                <button type="button" onclick="adjustQty({{ $food->id }}, 1)" class="w-9 h-9 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold flex items-center justify-center transition active:scale-95">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-dashed border-slate-300">
                        Belum ada menu yang aktif di sistem.
                    </div>
                @endforelse
            </div>

            <!-- Bottom Floating Action Bar -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-lg z-40">
                <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Ringkasan Sementara:</p>
                        <p class="text-lg font-bold text-slate-900">
                            <span id="floatingTotalCount">0 Porsi</span> &bull; <span id="floatingTotalPrice" class="text-emerald-700">Rp 0</span>
                        </p>
                    </div>
                    <button type="button" onclick="openReviewModal()" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-sm transition active:scale-95 flex items-center gap-2">
                        <span>Lanjut ke Pembayaran</span>
                    </button>
                </div>
            </div>

            <!-- Accessible Confirmation Modal -->
            <div id="reviewModal" class="fixed inset-0 bg-slate-950/60 hidden items-center justify-center z-50 p-4 backdrop-blur-xs">
                <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Konfirmasi Ringkasan Pesanan</h3>
                        <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold leading-none">&times;</button>
                    </div>

                    <div class="space-y-2.5 text-xs text-slate-600 mb-4 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <div class="flex justify-between">
                            <span class="font-medium text-slate-500">Nama Pelanggan:</span>
                            <span id="modalCustomerName" class="font-bold text-slate-900">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-slate-500">Nomor Meja:</span>
                            <span id="modalTableNumber" class="font-bold text-slate-900">-</span>
                        </div>
                    </div>

                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Item Yang Dipesan:</p>
                    <div class="max-h-56 overflow-y-auto divide-y divide-slate-100 border border-slate-200 rounded-xl mb-4 p-2 bg-white">
                        <ul id="modalItemList" class="space-y-1.5 text-xs"></ul>
                    </div>

                    <div class="flex justify-between items-center py-3 border-t border-slate-200 mb-6">
                        <span class="text-sm font-bold text-slate-700">Total Tagihan:</span>
                        <span id="modalGrandTotal" class="text-xl font-extrabold text-emerald-700">Rp 0</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="closeReviewModal()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                            Ubah Pesanan
                        </button>
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                            Ya, Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </main>

    <script>
        function setCategoryFilter(category, buttonElement) {
            document.querySelectorAll('.category-pill').forEach(btn => {
                btn.className = "category-pill px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-600 border border-slate-300 hover:bg-slate-100 transition";
            });
            buttonElement.className = "category-pill px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white transition";

            const items = document.querySelectorAll('.food-item');
            items.forEach(item => {
                const itemCat = item.getAttribute('data-category');
                item.style.display = (category === 'all' || itemCat === category) ? 'flex' : 'none';
            });
        }

        function adjustQty(foodId, delta) {
            const input = document.getElementById('qty-' + foodId);
            if (!input) return;
            let current = parseInt(input.value) || 0;
            current = Math.max(0, current + delta);
            input.value = current;
            recalculateSummary();
        }

        function recalculateSummary() {
            const inputs = document.querySelectorAll('.item-input');
            let totalQty = 0;
            let grandTotal = 0;

            inputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    const price = parseFloat(input.getAttribute('data-price')) || 0;
                    totalQty += qty;
                    grandTotal += (qty * price);
                }
            });

            document.getElementById('floatingTotalCount').textContent = totalQty + ' Porsi';
            document.getElementById('floatingTotalPrice').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        function openReviewModal() {
            const name = document.getElementById('customer_name').value.trim();
            const table = document.getElementById('table_number').value.trim();

            if (!name) {
                alert('Silakan isi Nama Pemesan terlebih dahulu!');
                document.getElementById('customer_name').focus();
                return;
            }

            if (!table) {
                alert('Silakan isi Nomor Meja Anda!');
                document.getElementById('table_number').focus();
                return;
            }

            const inputs = document.querySelectorAll('.item-input');
            let grandTotal = 0;
            let listHtml = '';
            let hasItems = false;

            inputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    hasItems = true;
                    const name = input.getAttribute('data-name');
                    const price = parseFloat(input.getAttribute('data-price')) || 0;
                    const subtotal = qty * price;
                    grandTotal += subtotal;

                    listHtml += `
                        <li class="flex items-center justify-between py-1.5 px-2">
                            <div>
                                <span class="font-bold text-slate-900">${name}</span>
                                <span class="text-[11px] text-slate-500 block">${qty} x Rp ${price.toLocaleString('id-ID')}</span>
                            </div>
                            <span class="font-semibold text-slate-800">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        </li>
                    `;
                }
            });

            if (!hasItems) {
                alert('Silakan pilih minimal 1 porsi menu sebelum lanjut!');
                return;
            }

            document.getElementById('modalCustomerName').textContent = name;
            document.getElementById('modalTableNumber').textContent = table;
            document.getElementById('modalItemList').innerHTML = listHtml;
            document.getElementById('modalGrandTotal').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

            const modal = document.getElementById('reviewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
