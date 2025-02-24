<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateJobApplicationsStatusOptions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM(
            'applied',
            'approved',
            'accepted',
            'declined',
            'rejected',
            'withdrawn'
        ) NOT NULL DEFAULT 'applied'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM(
            'applied',
            'accepted',
            'rejected'
        ) NOT NULL DEFAULT 'applied'");
    }
}
