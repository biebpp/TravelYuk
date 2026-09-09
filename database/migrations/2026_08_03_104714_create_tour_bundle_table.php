<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tour_bundles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transportation_id')->nullable()->constrained('transportations')->onDelete('cascade');

            $table->string('name');
            $table->text('description');
            $table->double('price');
            $table->integer('slot')->nullable();
            $table->double('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tour_bundles');
    }
};
