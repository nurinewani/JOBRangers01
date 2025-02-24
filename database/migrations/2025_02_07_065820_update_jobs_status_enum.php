<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateJobsStatusEnum extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records to use new status values
        DB::statement("UPDATE jobs SET status = 'open' WHERE status = 'pending'");
        
        // No need to modify column type since it's already varchar
        // Just update the values to match new statuses
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status changes if needed
        DB::statement("UPDATE jobs SET status = 'pending' WHERE status = 'open'");
    }
}
