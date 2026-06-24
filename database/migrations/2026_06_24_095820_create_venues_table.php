<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pengelola venue (Mhs 2)
            $table->string('name'); // nama gedung/lapangan
            $table->string('type')->default('hall'); // hall, lapangan_outdoor, ballroom, dll
            $table->text('address');
            $table->string('city'); // dipakai Mhs 3 untuk cek cuaca berdasarkan kota
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('capacity')->default(0); // kapasitas tamu
            $table->decimal('rental_price', 15, 2)->default(0);
            $table->string('photo')->nullable(); // path foto venue
            $table->text('facilities')->nullable(); // fasilitas, misal "AC, Sound System, Parkir"
            $table->string('status')->default('available'); // available, booked, maintenance
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
