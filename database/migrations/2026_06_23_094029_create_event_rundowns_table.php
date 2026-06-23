<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_rundowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('activity'); // nama kegiatan, misal "Registrasi Tamu"
            $table->time('start_time');
            $table->time('end_time');
            $table->string('person_in_charge')->nullable(); // penanggung jawab
            $table->text('notes')->nullable();
            $table->integer('order')->default(0); // urutan tampil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_rundowns');
    }
};
