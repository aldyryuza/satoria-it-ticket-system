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
        Schema::create('ticket_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();

            $table->foreignId('company_id')->constrained();
            $table->foreignId('division_id')->constrained();
            $table->foreignId('requester_id')->constrained('users');

            $table->string('title');
            $table->text('description');

            $table->string('request_type');
            $table->string('urgency_level');

            $table->string('status');

            $table->integer('current_step')->nullable();
            $table->foreignId('current_approver')->nullable()->constrained('users');

            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('assigned_by')->nullable()->constrained('users');

            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_requests');
    }
};
