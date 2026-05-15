<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {

            $table->id();

            // IMPORTANT: must match your real table name
            $table->foreignId('class_id')
                ->constrained('school_classes')
                ->onDelete('cascade');

            $table->foreignId('fee_category_id')
                ->constrained('fee_categories')
                ->onDelete('cascade');

            $table->decimal('amount', 10, 2);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};