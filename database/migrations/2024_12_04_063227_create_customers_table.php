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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('address')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('software')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('customers')->insert([
            [
                'name' => 'John Doe',
                'email' => 'a@example.com',
                'password' => bcrypt('password'),
                'phone' => '0986254879',
                'address' => '123 Main St',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'profile_picture' => 'john_doe.jpg',
                'software' => 'Software A',
                'website' => 'https://johndoe.com',
                'tax_number' => '123456789',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'b@example.com',
                'password' => bcrypt('password'),
                'phone' => '0393849283',
                'address' => '456 Elm St',
                'gender' => 'female',
                'date_of_birth' => '1985-05-15',
                'profile_picture' => 'jane_smith.jpg',
                'software' => 'Software B',
                'website' => 'https://janesmith.com',
                'tax_number' => '987654321',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
