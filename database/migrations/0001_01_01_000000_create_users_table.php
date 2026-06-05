<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles');
            $table->string('full_name', 150);
            $table->string('login', 100)->unique();
            $table->string('password', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
