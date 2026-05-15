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
        Schema::create('expenses', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | BASIC EXPENSE INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('title');
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | EXPENSE CLASSIFICATION
            |--------------------------------------------------------------------------
            */

            $table->string('category')->nullable();
            $table->date('expense_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | RELATIONSHIP (WHO PAID IT)
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};