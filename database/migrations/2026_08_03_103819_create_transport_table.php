<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('serial_number');
            $table->string('brand');
            $table->string('variant');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transportations');
    }
};

