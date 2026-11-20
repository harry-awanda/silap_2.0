<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->string('username', 50)->nullable()->unique();
      $table->boolean('must_change_password')->default(true);
      $table->timestamp('password_changed_at')->nullable();
      $table->timestamp('disabled_at')->nullable();
      $table->string('email')->nullable()->change();
      $table->dropColumn('email_verified_at');
    });

    DB::table('users')->select('id')->orderBy('id')->chunkById(100, function ($users) {
      foreach ($users as $user) {
        DB::table('users')->where('id', $user->id)->update(['username' => 'user-'.$user->id]);
      }
    });

    Schema::table('users', fn(Blueprint $table) => $table->string('username', 50)->nullable(false)->change());
    Schema::dropIfExists('password_reset_tokens');
  }

  public function down(): void {
    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('email')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    Schema::table('users', function (Blueprint $table) {
      $table->timestamp('email_verified_at')->nullable();
      $table->dropUnique(['username']);
      $table->dropColumn(['username', 'must_change_password', 'password_changed_at', 'disabled_at']);
    });
  }
};
