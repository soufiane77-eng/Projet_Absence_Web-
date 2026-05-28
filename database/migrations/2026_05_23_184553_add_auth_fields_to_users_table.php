<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'teacher', 'student'])
                  ->default('student')
                  ->after('password');
            $table->string('avatar')->nullable()->after('role');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->boolean('is_active')->default(true)->after('phone');
            $table->tinyInteger('login_attempts')->unsigned()->default(0)->after('is_active');
            $table->dateTime('locked_until')->nullable()->after('login_attempts');
            $table->string('last_login_ip', 45)->nullable()->after('locked_until');
            $table->dateTime('last_login_at')->nullable()->after('last_login_ip');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'avatar', 'phone', 'is_active',
                'login_attempts', 'locked_until',
                'last_login_ip', 'last_login_at',
            ]);
        });
    }
};
