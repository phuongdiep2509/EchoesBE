<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm các cột GoogleId, ResetToken, ResetTokenExpiry vào bảng tai_khoan.
     * Các cột này hỗ trợ đăng nhập bằng Google và chức năng quên mật khẩu.
     */
    public function up(): void
    {
        Schema::table('tai_khoan', function (Blueprint $table) {
            if (!Schema::hasColumn('tai_khoan', 'GoogleId')) {
                $table->string('GoogleId', 100)->nullable()->after('SoDienThoai');
            }
            if (!Schema::hasColumn('tai_khoan', 'ResetToken')) {
                $table->string('ResetToken', 64)->nullable()->after('GoogleId');
            }
            if (!Schema::hasColumn('tai_khoan', 'ResetTokenExpiry')) {
                $table->dateTime('ResetTokenExpiry')->nullable()->after('ResetToken');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tai_khoan', function (Blueprint $table) {
            $table->dropColumn(['GoogleId', 'ResetToken', 'ResetTokenExpiry']);
        });
    }
};
