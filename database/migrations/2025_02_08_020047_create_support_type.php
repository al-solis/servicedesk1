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
            ['description' => 'Accounting & Finance']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Admin & General Services']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Client Services']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Compliance & Legal']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Human Resources (HR)']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Incident & Emergency Reports']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'IT & Technical Support']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Logistics & Supplies']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Payroll & Compensation']
        ]);
        DB::table('support_type')->insert([
            ['description' => 'Security Operations']
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
