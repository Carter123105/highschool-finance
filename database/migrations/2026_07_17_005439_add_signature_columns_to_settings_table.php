<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->string('authorized_signature')
                  ->nullable()
                  ->after('logo');

            $table->string('registrar_signature')
                  ->nullable()
                  ->after('authorized_signature');

        });
    }


    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->dropColumn([
                'authorized_signature',
                'registrar_signature'
            ]);

        });
    }
};