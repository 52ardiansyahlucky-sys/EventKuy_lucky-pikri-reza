<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pemilik event
            $table->string('name'); // Nama Event
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->decimal('total_budget', 15, 2)->default(0); // Anggaran total
            $table->string('status')->default('draft'); // draft, planned, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
