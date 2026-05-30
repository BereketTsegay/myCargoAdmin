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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['shipping', 'transitor', 'customer','shipper','vessel'])->default('customer');
            $table->foreignId('vessel_shipping_id')->nullable()->constrained('parties')->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();
        });

        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->string('container_number')->unique();
            $table->string('booking_number')->unique();
            $table->string('seal_number')->unique();
            $table->foreignId('transitor_id')->constrained('parties')->onDelete('cascade');
            $table->foreignId('shipping_id')->constrained('parties')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('parties')->onDelete('cascade');
            $table->foreignId('shipper_id')->constrained('parties')->onDelete('cascade');
            $table->foreignId('vessel_id')->constrained('parties')->onDelete('cascade');
            $table->string('voyage_number')->nullable();
            $table->enum('container_type', ['20ft', '40ft', '40ft_high_cube'])->default('40ft_high_cube');
            $table->boolean('is_soc')->default(false);
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->string('origin_port')->nullable()->default('Jeble Ali, UAE');
            $table->string('destination_port')->nullable()->default('Massawa, Eritrea');
            $table->enum('status', ['in_transit', 'arrived', 'departed'])->default('in_transit');
            $table->boolean('is_group_container')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
        Schema::dropIfExists('parties');
    }
};
