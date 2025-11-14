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
        Schema::create('contact_merge_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_contact_id')->index();
            $table->unsignedBigInteger('merged_contact_id')->index();
            $table->json('merged_data'); // snapshot of what was merged
            $table->foreign('master_contact_id')->references('id')->on('contacts')->cascadeOnDelete();
            $table->foreign('merged_contact_id')->references('id')->on('contacts')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_merge_logs');
    }
};
