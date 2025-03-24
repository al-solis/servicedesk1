<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_header', function (Blueprint $table) {
            $table->id();
            $table->string('description');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('priority', ['Low', 'Medium', 'High']);

            $table->unsignedBigInteger('type');
            $table->foreign('type')->references('id')->on('support_type');
            $table->enum('status', ['Open', 'Pending', 'In Progress', 'On-hold', 'Closed', 'Cancelled']);
            $table->dateTime('date_created');
            $table->dateTime('date_closed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_header');
    }
};
