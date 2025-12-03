<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('operasis', function (Blueprint $t) {
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
        });
    }
    public function down(): void
    {
        Schema::table('operasis', fn(Blueprint $t) => $t->dropConstrainedForeignId('user_id'));
    }
};
