<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->nullable();
            $table->string('job_id')->nullable();
            $table->longText('response')->nullable();
            $table->longText('status_response')->nullable();;
            $table->string('file_path')->nullable();;
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulk_requests');
    }
};
