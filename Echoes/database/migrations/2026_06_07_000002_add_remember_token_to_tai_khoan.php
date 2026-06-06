<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tai_khoan', function (Blueprint $table) {
            if (!Schema::hasColumn('tai_khoan', 'remember_token')) {
                $table->rememberToken()->after('TrangThai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tai_khoan', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
