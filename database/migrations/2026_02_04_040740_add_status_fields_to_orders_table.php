<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('status')->default('pending')->after('payment_status');
        $table->string('tracking_number')->nullable()->after('status');
        $table->string('slip_image')->nullable()->after('tracking_number');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['status', 'tracking_number', 'slip_image']);
    });
}
};
