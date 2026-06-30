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
            $table->integer('type')->default(0); // 0 for internal, 1 for external
            $table->unsignedBigInteger('default_group_id')->nullable();
            $table->foreign('default_group_id')->references('id')->on('support_team');
            $table->timestamps();
        });

        DB::table('support_type')->insert([
            ['description' => 'Accounting & Finance', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Admin & General Services', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Client Services', 'type' => 1]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Compliance & Legal', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Human Resources (HR)', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Incident & Emergency Reports', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'IT & Technical Support', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Logistics & Supplies', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Payroll & Compensation', 'type' => 0]
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Security Operations', 'type' => 0]
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
