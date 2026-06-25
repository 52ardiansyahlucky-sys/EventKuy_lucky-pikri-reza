<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');

            $table->date('forecast_date'); // tanggal yang diramal (biasanya = event_date)
            $table->string('weather_main')->nullable(); // "Rain", "Clouds", "Clear", dll dari API
            $table->string('weather_description')->nullable(); // "light rain", dll
            $table->decimal('temperature', 5, 2)->nullable(); // suhu (Celsius)
            $table->integer('humidity')->nullable(); // persen kelembapan
            $table->decimal('rain_probability', 5, 2)->nullable(); // % kemungkinan hujan (field "pop" dari API)
            $table->decimal('wind_speed', 5, 2)->nullable(); // m/s

            $table->string('recommendation_level')->nullable(); // aman, waspada, siaga
            $table->text('recommendation_text')->nullable(); // saran tenda/pawang otomatis

            $table->timestamp('checked_at'); // kapan data ini diambil dari API
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_reports');
    }
};
