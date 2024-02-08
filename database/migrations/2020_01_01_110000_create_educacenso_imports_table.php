<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducacensoImportsTable extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('public.educacenso_imports')) {
            Schema::create('public.educacenso_imports', function (
                Blueprint $table
            ): void {
                $table->id();
                $table->integer('year');
                $table->string('school');
                $table->integer('user_id');
                $table->boolean('finished');
                $table->boolean('error')->default(false);
                $table->date('registration_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('public.educacenso_imports');
    }
}
