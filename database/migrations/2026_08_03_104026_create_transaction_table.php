<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary(); 
            
            // Membuat kolom user_id sekaligus indeks foreign key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('payment');
            $table->double('nominal');
            $table->dateTime('transaction_date')->index(); 
            $table->string('status')->index(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
