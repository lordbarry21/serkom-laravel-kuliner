<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Menampilkan katalog menu makanan untuk pelanggan (sisi publik).
     */
    public function index(): View
    {
        $foods = Food::all();
        return view('customer.index', compact('foods'));
    }

    /**
     * Memproses checkout pesanan dari pelanggan secara atomik via DB Transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number'  => 'required|string|max:50',
            'items'         => 'required|array',
            'items.*'       => 'nullable|integer|min:0',
        ]);

        // Filter hanya item yang dipesan dengan jumlah > 0
        $orderedItems = array_filter($request->items, fn ($qty) => is_numeric($qty) && (int)$qty > 0);

        if (empty($orderedItems)) {
            return back()->with('error', 'Silakan pilih minimal 1 porsi menu makanan atau minuman!');
        }

        DB::beginTransaction();
        try {
            // 1. Buat data induk Order (status awal: 'Pending')
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'table_number'  => $request->table_number,
                'total_price'   => 0,
                'status'        => 'Pending', // Menggunakan format Title Case sesuai ENUM migration
            ]);

            $totalPrice = 0;

            // 2. Iterasi setiap item yang dipesan, hitung subtotal & simpan ke order_details
            foreach ($orderedItems as $foodId => $quantity) {
                $food = Food::findOrFail($foodId);
                $subtotal = $food->price * (int)$quantity;
                $totalPrice += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'food_id'  => $food->id,
                    'quantity' => (int)$quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            // 3. Update total akhir pesanan induk
            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('customer.index')->with(
                'success',
                "Pesanan #{$order->id} berhasil dibuat! Meja: {$order->table_number}. Total: Rp " . number_format($totalPrice, 0, ',', '.')
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard monitoring rekap pesanan masuk untuk admin & kasir.
     * Menggunakan Eager Loading 'orderDetails.food' untuk efisiensi query (No N+1 Issue).
     */
    public function adminDashboard(): View
    {
        $orders = Order::with('orderDetails.food')->latest()->get();
        return view('dashboard', compact('orders'));
    }

    /**
     * Memperbarui status pesanan dari dashboard admin.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:Pending,Diproses,Selesai,Batal',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', "Status pesanan #{$order->id} berhasil diperbarui menjadi {$order->status}!");
    }
}
