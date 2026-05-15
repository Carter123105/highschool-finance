<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            // add missing financial fields
            $table->decimal('discount', 12, 2)->default(0)->after('amount');

            $table->decimal('subtotal', 12, 2)->default(0)->after('discount');

            $table->string('slip_no')->nullable()->after('subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            $table->dropColumn([
                'discount',
                'subtotal',
                'slip_no',
            ]);
        });
    }
};