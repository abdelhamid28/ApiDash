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
        Schema::create('my_learning', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('cover');
            $table->integer('study_id');
            $table->integer('term_id');
            $table->integer('subject_id');
            $table->text('content');
            $table->string('creator_name');
            $table->string('creator_phone');
            $table->string('creator_position');
            $table->integer('totalQuestions');
            $table->tinyInteger('status')->default(1);
            $table->text('extra_field')->nullable();
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
        Schema::dropIfExists('my_learning');
    }
};
