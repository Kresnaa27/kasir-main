<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Hanya kolom 'id' saja yang auto_increment primary key
            $table->string('invoice_number');
            $table->integer('total_amount'); // Ubah jadi integer biasa
            $table->integer('paid_amount');  // Ubah jadi integer biasa
            $table->integer('change_amount');// Ubah jadi integer biasa
            $table->string('payment_method');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};