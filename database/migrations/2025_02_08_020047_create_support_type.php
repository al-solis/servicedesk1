<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('support_type', function (Blueprint $table) {
            $table->id();
            $table->string('description');

            $table->unsignedBigInteger('default_group_id')->nullable();
            $table->foreign('default_group_id')->references('id')->on('support_team');
            $table->timestamps();
        });

        DB::table('support_type')->insert([
            ['description' => 'Hardware']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Software']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Network']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_type');
    }
};
