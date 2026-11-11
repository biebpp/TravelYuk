<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('category')->default('other');
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(false);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('type')->default('admission');
            $table->unsignedInteger('quota')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::table('tour_bundles', function (Blueprint $table) {
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('duration_days')->default(1);
            $table->unsignedSmallInteger('duration_nights')->default(0);
            $table->json('facilities')->nullable();
            $table->date('departure_date')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code')->nullable()->unique();
            $table->uuid('idempotency_key')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->date('departure_date')->nullable();
            $table->unsignedSmallInteger('participants')->default(1);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->boolean('inventory_reserved')->default(false);
            $table->boolean('bundle_quota_reserved')->default(false);
        });

        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ticket_name');
            $table->decimal('unit_price', 12, 2);
            $table->unsignedSmallInteger('quantity');
            $table->boolean('quota_reserved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_items');

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['booking_code']);
            $table->dropUnique(['idempotency_key']);
            $table->dropColumn([
                'booking_code',
                'idempotency_key',
                'phone',
                'email',
                'departure_date',
                'participants',
                'total_price',
                'payment_status',
                'inventory_reserved',
                'bundle_quota_reserved',
            ]);
        });

        Schema::table('tour_bundles', function (Blueprint $table) {
            $table->dropColumn([
                'image_path',
                'duration_days',
                'duration_nights',
                'facilities',
                'departure_date',
                'is_active',
            ]);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'quota',
                'valid_from',
                'valid_until',
                'is_active',
            ]);
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn(['category', 'image_path', 'is_featured']);
        });
    }
};
