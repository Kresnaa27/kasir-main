<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        // Pastikan dua relasi ini ada
        $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete(); 
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); 
        
        $table->string('invoice_number');
        $table->decimal('total_amount', 15, 2);
        $table->decimal('pay_amount', 15, 2)->default(0); 
        $table->decimal('paid_amount', 15, 2)->default(0);
        $table->decimal('change_amount', 15, 2);
        $table->string('payment_method');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};