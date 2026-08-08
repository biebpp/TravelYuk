<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bundle_pivots', function (Blueprint $table) {
            $table->id(); // PK
            
            // Dua FK Utama yang otomatis diindeks agar query pencarian rute destinasi cepat
            $table->foreignId('bundle_id')->constrained('tour_bundles')->onDelete('cascade');
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('bundle_pivots');
    }
};

