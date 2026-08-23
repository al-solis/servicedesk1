<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('telegram_accounts', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            /*
             * Telegram's from.id
             */
            $table->string('telegram_user_id')->unique();
            $table->string('telegram_username')->nullable();
            $table->string('telegram_first_name')->nullable();
            $table->string('telegram_last_name')->nullable();
            $table->dateTime('linked_at')->nullable();
            $table->enum('status', [
                'Active',
                'Blocked'
            ])->default('Active');

            $table->timestamps();

            /*
             * One ISMS account = one Telegram account
             */
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_accounts');
    }
};