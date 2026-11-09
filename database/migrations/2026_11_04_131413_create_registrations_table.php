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
    Schema::create('registrations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('event_id')->constrained()->cascadeOnDelete();
        $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
        $table->string('status'); // registered or waitlisted
        $table->timestamps();

        $table->unique(['event_id', 'participant_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('registrations');
}
};
