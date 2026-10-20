<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('tags', function (Blueprint $table) {
        $table->string('color', 7)->default('#0D9488');
    });
}

public function down(): void
{
    Schema::table('tags', function (Blueprint $table) {
        $table->dropColumn('color');
    });
}
};
