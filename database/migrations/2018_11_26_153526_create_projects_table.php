<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id');
            $table->text('title');
            $table->text('description')->nullable();
            $table->boolean('UG');
            $table->boolean('MSc');
            $table->boolean('experimental');
            $table->boolean('computational');
            $table->boolean('hidden');
            $table->integer('popularity');
            $table->integer('selected_user_id')->nullable();
            $table->integer('selected_user2_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
