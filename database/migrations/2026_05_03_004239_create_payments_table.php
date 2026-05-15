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

    $table->string('receipt_no')->unique();

    $table->foreignId('invoice_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('student_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->decimal('amount_paid', 12, 2);

    $table->enum('payment_method', [
        'Cash',
        'Mobile Money',
        'Bank'
    ]);

    $table->string('transaction_reference')->nullable();

    $table->foreignId('received_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->date('payment_date');

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
