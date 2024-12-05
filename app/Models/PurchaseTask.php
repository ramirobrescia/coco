<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseTask extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    } 

    public function task() {
        return $this->belongsTo(Task::class);
    } 
}
