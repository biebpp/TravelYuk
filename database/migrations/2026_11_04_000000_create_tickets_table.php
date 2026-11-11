<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });

        Schema::create('bundle_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('tour_bundles')->cascadeOnDelete();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['bundle_id', 'ticket_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_tickets');
        Schema::dropIfExists('tickets');
    }
};
