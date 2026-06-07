<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ve_tang')) {
            return;
        }

        Schema::table('ve_tang', function (Blueprint $table) {
            if (!Schema::hasColumn('ve_tang', 'TrangThai')) {
                $table->string('TrangThai', 50)->default('DangChoNhan')->after('LoiChuc');
            }

            if (!Schema::hasColumn('ve_tang', 'TokenNhanVe')) {
                $table->string('TokenNhanVe', 100)->nullable()->unique()->after('TrangThai');
            }

            if (!Schema::hasColumn('ve_tang', 'ThoiGianTang')) {
                $table->dateTime('ThoiGianTang')->nullable()->after('TokenNhanVe');
            }

            if (!Schema::hasColumn('ve_tang', 'ThoiGianNhan')) {
                $table->dateTime('ThoiGianNhan')->nullable()->after('ThoiGianTang');
            }
        });

        DB::table('ve_tang')
            ->whereNull('TrangThai')
            ->orWhere('TrangThai', '')
            ->update(['TrangThai' => 'DangChoNhan']);
    }

    public function down(): void
    {
        if (!Schema::hasTable('ve_tang')) {
            return;
        }

        Schema::table('ve_tang', function (Blueprint $table) {
            if (Schema::hasColumn('ve_tang', 'ThoiGianNhan')) {
                $table->dropColumn('ThoiGianNhan');
            }

            if (Schema::hasColumn('ve_tang', 'ThoiGianTang')) {
                $table->dropColumn('ThoiGianTang');
            }

            if (Schema::hasColumn('ve_tang', 'TokenNhanVe')) {
                $table->dropUnique(['TokenNhanVe']);
                $table->dropColumn('TokenNhanVe');
            }

            if (Schema::hasColumn('ve_tang', 'TrangThai')) {
                $table->dropColumn('TrangThai');
            }
        });
    }
};
