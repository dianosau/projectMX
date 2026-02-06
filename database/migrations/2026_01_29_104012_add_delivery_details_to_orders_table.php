<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('status')->default('pending')->change(); // สถานะ: pending, picked_up, shipping, delivered
        $table->timestamp('picked_up_at')->nullable(); // เวลาที่รถมารับ
        $table->timestamp('shipping_at')->nullable();  // เวลาที่เริ่มส่ง
        $table->timestamp('delivered_at')->nullable(); // เวลาที่ถึงมือลูกค้า
        $table->string('tracking_number')->nullable(); // เลขพัสดุ
    });
}
};
