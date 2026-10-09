<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FoodController extends Controller
{
    /**
     * Menampilkan daftar katalog seluruh menu makanan di panel admin.
     */
    public function index(): View
    {
        $foods = Food::latest()->paginate(10);
        return view('admin.foods.index', compact('foods'));
    }

    /**
     * Menampilkan form input penambahan menu makanan baru.
     */
    public function create(): View
    {
        return view('admin.foods.create');
    }

    /**
     * Menyimpan data menu makanan baru ke database beserta upload foto.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Makanan,Minuman,Cemilan',
            'price'       => 'required|numeric|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('foods', 'public');
        }

        Food::create([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'price'       => $validated['price'],
            'description' => $validated['description'],
            'image'       => $imagePath,
        ]);

        return redirect()->route('foods.index')->with('success', 'Data menu berhasil ditambahkan ke katalog!');
    }

    /**
     * Menampilkan form edit menu makanan.
     */
    public function edit(Food $food): View
    {
        return view('admin.foods.edit', compact('food'));
    }

    /**
     * Memperbarui data menu makanan dan mengganti gambar fisik lama jika ada file baru.
     */
    public function update(Request $request, Food $food): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Makanan,Minuman,Cemilan',
            'price'       => 'required|numeric|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $food->image;
        if ($request->hasFile('image')) {
            // Hapus foto lama di storage jika ada file baru
            if ($food->image && Storage::disk('public')->exists($food->image)) {
                Storage::disk('public')->delete($food->image);
            }
            $imagePath = $request->file('image')->store('foods', 'public');
        }

        $food->update([
            'name'        => $validated['name'],
            'category'    => $validated['category'],
            'price'       => $validated['price'],
            'description' => $validated['description'],
            'image'       => $imagePath,
        ]);

        return redirect()->route('foods.index')->with('success', 'Data menu berhasil diperbarui!');
    }

    /**
     * Menghapus menu makanan dan membersihkan file foto terkait dari storage.
     */
    public function destroy(Food $food): RedirectResponse
    {
        if ($food->image && Storage::disk('public')->exists($food->image)) {
            Storage::disk('public')->delete($food->image);
        }

        $food->delete();

        return redirect()->route('foods.index')->with('success', 'Data menu berhasil dihapus dari sistem!');
    }
}
