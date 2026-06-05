<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->foreignId('pickup_point_id')->constrained('pickup_points');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->unsignedInteger('receive_code');
            $table->foreignId('status_id')->constrained('order_statuses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
