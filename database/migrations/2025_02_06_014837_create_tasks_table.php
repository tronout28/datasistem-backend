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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('produksi_id_1')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('produksi_id_2')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('produksi_id_3')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('paket_id')->nullable()->constrained('pakets')->nullOnDelete();
            
            $table->string('task_name')->nullable();
            $table->string('bisnis_name')->nullable();
            $table->string('bisnis_domain')->nullable();
            $table->integer('queue')->default(0);
            $table->enum('status', ['open','pending', 'on-progress', 'completed'])->default('open');
            $table->date('deadline')->nullable();
            $table->date('join_date')->nullable();
            $table->text('note')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
