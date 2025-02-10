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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->foreignId('produksi_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('report_name')->nullable();
            $table->enum('status', ['open','pending', 'on-progress', 'completed'])->default('open');
            $table->date('report_date')->nullable();

            $table->text('note')->nullable();
            $table->string('image_report')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
