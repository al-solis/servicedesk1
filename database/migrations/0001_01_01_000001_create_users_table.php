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
            $table->string('empid')->nullable();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
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
            'email' => 'support1@yahoo.com',
            'password' => Hash::make('support1'),
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
            'password' => Hash::make('user'),
            'usertype' => 'User',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00004',
            'lname' => 'Accountant',
            'fname' => '1',
            'mname' => '',
            'email' => 'accountant1@yahoo.com',
            'password' => Hash::make('accountant1'),
            'usertype' => 'Support Team',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00005',
            'lname' => 'Accountant',
            'fname' => '2',
            'mname' => '',
            'email' => 'accountant2@yahoo.com',
            'password' => Hash::make('accountant2'),
            'usertype' => 'Support Team',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00006',
            'lname' => 'Payroll',
            'fname' => '1',
            'mname' => '',
            'email' => 'payroll1@yahoo.com',
            'password' => Hash::make('payroll1'),
            'usertype' => 'Support Team',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00007',
            'lname' => 'Payroll',
            'fname' => '2',
            'mname' => '',
            'email' => 'payroll2@yahoo.com',
            'password' => Hash::make('payroll2'),
            'usertype' => 'Support Team',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00008',
            'lname' => 'HR Manager',
            'fname' => '1',
            'mname' => '',
            'email' => 'hrmanager1@yahoo.com',
            'password' => Hash::make('hrmanager1'),
            'usertype' => 'Support Team',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00009',
            'lname' => 'Guard',
            'fname' => '1',
            'mname' => '',
            'email' => 'guard1@yahoo.com',
            'password' => Hash::make('guard1'),
            'usertype' => 'User',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00010',
            'lname' => 'Guard',
            'fname' => '2',
            'mname' => '',
            'email' => 'guard2@yahoo.com',
            'password' => Hash::make('guard2'),
            'usertype' => 'User',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00011',
            'lname' => 'Guard',
            'fname' => '3',
            'mname' => '',
            'email' => 'guard3@yahoo.com',
            'password' => Hash::make('guard3'),
            'usertype' => 'User',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00012',
            'lname' => 'Support',
            'fname' => '2',
            'mname' => 'M.',
            'email' => 'support2@yahoo.com',
            'password' => Hash::make('support2'),
            'usertype' => 'Support Team',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'empid' => '00013',
            'lname' => 'COO',
            'fname' => '1',
            'mname' => '',
            'email' => 'coo1@yahoo.com',
            'password' => Hash::make('coo1'),
            'usertype' => 'Administrator',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'empid' => null,
            'lname' => 'Client',
            'fname' => '1',
            'mname' => '',
            'email' => 'client1@yahoo.com',
            'password' => Hash::make('client1'),
            'usertype' => 'Client',
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'empid' => null,
            'lname' => 'Client',
            'fname' => '2',
            'mname' => '',
            'email' => 'client2@yahoo.com',
            'password' => Hash::make('client2'),
            'usertype' => 'Client',
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
