<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('educacenso_situation_imports', function (Blueprint $table): void {
            $table->id();
            $table->smallInteger('year');
            $table->string('school_name');
            $table->bigInteger('user_id');
            $table->smallInteger('status_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educacenso_situation_imports');
    }
};
