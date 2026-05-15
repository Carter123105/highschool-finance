<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('class_id')
                ->nullable()
                ->after('student_id');

            $table->foreignId('section_id')
                ->nullable()
                ->after('class_id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['class_id', 'section_id']);
        });
    }
};