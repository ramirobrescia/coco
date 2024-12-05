<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\User::class);
            $table->foreignIdFor(App\Models\Provider::class)->constrained();
            $table->string('price_list')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('purchase_task', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Task::class)->nullable(false)->index();
            $table->foreignIdFor(App\Models\Purchase::class)->nullable(false)->index();
            $table->foreignIdFor(App\Models\User::class)->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('task');
        Schema::dropIfExists('purchase_task');
    }
};
