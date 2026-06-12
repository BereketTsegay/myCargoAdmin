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
        Schema::table('containers', function (Blueprint $table) {
            //
            $table->foreignId('origin_port_id')->nullable()->constrained('ports')->onDelete('set null')->change();
            $table->foreignId('destination_port_id')->nullable()->constrained('ports')->onDelete('set null')->change();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->string('origin_port')->nullable()->default('Jeble Ali, UAE')->change();
            $table->string('destination_port')->nullable()->default('Massawa, Eritrea')->change();
        });
    }
};
