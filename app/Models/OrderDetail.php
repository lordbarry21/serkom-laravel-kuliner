<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    /**
     * Explicit table name.
     */
    protected $table = 'order_details';

    /**
     * Menggunakan guarded ['id'] agar kolom 'subtotal' diizinkan
     * masuk ke database tanpa terblokir mass assignment protection.
     */
    protected $guarded = ['id'];

    /**
     * Relasi Many-to-One: Setiap baris item detail terhubung ke satu menu Food.
     */
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    /**
     * Relasi Many-to-One: Setiap baris item detail terhubung ke satu pesanan Order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
