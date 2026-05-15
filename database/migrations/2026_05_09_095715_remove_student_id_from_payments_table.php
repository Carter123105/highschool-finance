<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            // 1. drop foreign key FIRST
            if (Schema::hasColumn('payments', 'student_id')) {

                $table->dropForeign(['student_id']);

                // 2. then drop column
                $table->dropColumn('student_id');
            }

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->unsignedBigInteger('student_id')->nullable();

            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');
        });
    }
};