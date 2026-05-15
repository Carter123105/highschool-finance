<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            if (!Schema::hasColumn('invoices', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable()->after('student_id');
            }

            if (!Schema::hasColumn('invoices', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            }

            if (!Schema::hasColumn('invoices', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->nullable()->after('section_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['class_id', 'section_id', 'academic_year_id']);
        });
    }
};