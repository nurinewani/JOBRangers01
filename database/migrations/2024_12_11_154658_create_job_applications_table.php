<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id(); // Auto-incrementing BIGINT for primary key
            $table->unsignedInteger('user_id'); // Ensure user_id matches the type of users.id (BIGINT)
            $table->unsignedBigInteger('job_id'); // Ensure job_id matches the type of jobs.id (BIGINT)
            $table->enum('status', ['applied', 'accepted', 'rejected'])->default('applied');
            $table->timestamps();
        
            // Add the foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
}
