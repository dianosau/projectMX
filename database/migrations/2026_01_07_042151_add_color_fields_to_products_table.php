<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // เพิ่มชื่อสี เช่น 'Black', 'Sunburst'
            $table->string('color')->nullable()->after('name'); 
            
            // เพิ่มโค้ดสี HEX เช่น '#000000' เอาไว้ทำปุ่มวงกลมสีในหน้าเว็บ
            $table->string('color_hex', 7)->nullable()->after('color'); 
            
            // เพิ่มสถานะสินค้า (เปิด/ปิด การขาย)
            $table->boolean('is_active')->default(true)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['color', 'color_hex', 'is_active']);
        });
    }
};
