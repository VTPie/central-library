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
        Schema::create('order_imports', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->enum('status', [
                config('app.import_statuses.pending'),
                config('app.import_statuses.processing'),
                config('app.import_statuses.completed'),
                config('app.import_statuses.failed'),
            ])->default(config('app.import_statuses.pending'));

            $table->unsignedBigInteger('total_rows')
                ->default(0);

            $table->unsignedBigInteger('processed_rows')
                ->default(0);

            $table->unsignedBigInteger('failed_rows')
                ->default(0);

            $table->unsignedTinyInteger('progress')
                ->default(0);

            $table->string('error_file_path')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_imports');
    }
};