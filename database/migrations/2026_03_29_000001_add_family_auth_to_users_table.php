<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->nullable()->after('id');
            $table->string('username')->nullable()->after('name');
            $table->boolean('is_super_admin')->default(false)->after('password');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('person_id')->references('id')->on('people')->nullOnDelete();
        });

        foreach (DB::table('users')->orderBy('id')->get() as $u) {
            $username = $u->name === 'admin'
                ? 'admin'
                : Str::slug((string) $u->name).'-'.$u->id;

            DB::table('users')->where('id', $u->id)->update([
                'username' => $username,
                'is_super_admin' => $u->name === 'admin',
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->change();
            $table->unique('person_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['person_id']);
            $table->dropUnique(['username']);
            $table->dropForeign(['person_id']);
            $table->dropColumn(['person_id', 'username', 'is_super_admin']);
        });
    }
};
