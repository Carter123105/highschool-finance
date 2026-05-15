<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_categories', function (Blueprint $table) {

            $table->id();

            // MAIN LABEL (what users see)
            $table->string('name')->unique(); 
            // e.g. Tuition Fee, Registration Fee, Exam Fee

            // OPTIONAL DESCRIPTION
            $table->text('description')->nullable();

            // TYPE OF PAYMENT
            $table->enum('type', [
                'Mandatory',
                'Optional'
            ])->default('Mandatory');

            // BILLING STYLE
            $table->boolean('is_monthly')->default(false);

            // STATUS
            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_categories');
    }
};