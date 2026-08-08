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
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->index();
            $table->string('username', 20)->unique();
            $table->string('password',100);
            $table->string('name', 100);
            $table->string('email',100)->unique();
            $table->string('contact',25);
            $table->string('native',100);
            $table->date('birth');
            $table->string('gender',10);
            $table->string('profile',255);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
