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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('empid')->unique();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('usertype')->default('User');
            $table->string('designation')->nullable();
            $table->string('telno')->nullable();
            $table->string('profile_picture')->nullable();

            $table->unsignedBigInteger('dept_id')->nullable();
            $table->foreign('dept_id')->references('id')->on('department');

            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        DB::table('users')->insert([
            'empid' => '00001',
            'lname' => 'Solis',
            'fname' => 'Al',
            'mname' => 'B.',
            'email' => 'admin@yahoo.com',
            'password' => Hash::make('@dmin'),
            'usertype' => 'Administrator',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00002',
            'lname' => 'Support',
            'fname' => 'Team',
            'mname' => 'M.',
            'email' => 'support@yahoo.com',
            'password' => Hash::make('supp0rt'),
            'usertype' => 'Support Team',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00003',
            'lname' => 'User',
            'fname' => 'Sample',
            'mname' => 'M.',
            'email' => 'user@yahoo.com',
            'password' => Hash::make('us3r'),
            'usertype' => 'User',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
