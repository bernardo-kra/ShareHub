<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shared_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('recipient_id')->constrained('users'); 
            $table->foreignId('file_id')->constrained(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_files');
    }
};
