<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model {
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email', 'contact'];
    
    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
    
    // Crear proveedores y compras asociadas.
}
