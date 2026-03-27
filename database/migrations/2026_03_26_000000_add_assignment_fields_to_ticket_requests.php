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
        Schema::table('ticket_requests', function (Blueprint $table) {
            $table->date('plan_due_date')->nullable()->after('assigned_at');
            $table->text('assignment_note')->nullable()->after('plan_due_date');
            $table->timestamp('done_actual_date')->nullable()->after('done_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_requests', function (Blueprint $table) {
            $table->dropColumn(['plan_due_date', 'assignment_note', 'done_actual_date']);
        });
    }
};
