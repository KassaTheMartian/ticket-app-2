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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('ticket_types')->insert([
            ['name' => 'Repair', 'description' => 'Ticket type for repair'],
            ['name' => 'Replacement', 'description' => 'Ticket type for replacement'],
            ['name' => 'Maintenance', 'description' => 'Ticket type for maintenance'],
            ['name' => 'Consultation', 'description' => 'Ticket type for consultation'],
            ['name' => 'Installation', 'description' => 'Ticket type for installation'],
            ['name' => 'Upgrade', 'description' => 'Ticket type for upgrade'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
