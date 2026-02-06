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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // เชื่อมกับตาราง orders
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // ข้อมูลการชำระเงิน
            $table->string('method'); // เช่น 'transfer', 'credit_card', 'promptpay'
            $table->decimal('amount', 10, 2); // ยอดเงินที่จ่ายจริง
            // สำหรับการโอนเงิน (Manual Transfer)
            $table->string('payment_proof')->nullable(); // ชื่อไฟล์สลิป
            // สถานะการจ่ายเงิน
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};