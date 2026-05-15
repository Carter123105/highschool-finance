<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            // DROP FOREIGN KEY FIRST (IMPORTANT FIX)
            if (Schema::hasColumn('payments', 'invoice_item_id')) {
                $table->dropForeign(['invoice_item_id']);
            }

            if (Schema::hasColumn('payments', 'fee_category_id')) {
                $table->dropForeign(['fee_category_id']);
            }
        });

        // NOW DROP COLUMNS SAFELY
        Schema::table('payments', function (Blueprint $table) {

            if (Schema::hasColumn('payments', 'invoice_item_id')) {
                $table->dropColumn('invoice_item_id');
            }

            if (Schema::hasColumn('payments', 'fee_category_id')) {
                $table->dropColumn('fee_category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->foreignId('invoice_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('fee_category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }
};