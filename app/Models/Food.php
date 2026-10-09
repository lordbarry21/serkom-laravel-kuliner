<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Food extends Model
{
    use HasFactory;

    /**
     * Explicit table definition matching migration.
     */
    protected $table = 'foods';

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = ['id'];

    /**
     * Relasi One-to-Many: 1 Menu Makanan dapat berada di banyak detail pesanan.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'food_id');
    }
}
