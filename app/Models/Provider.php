<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model {
    use HasFactory;
    
    public function purchases() {
        return $this->hasMany(Purchase::class);
    }

    // Hacer factories
    // Crear proveedores y compras asociadas.
}
