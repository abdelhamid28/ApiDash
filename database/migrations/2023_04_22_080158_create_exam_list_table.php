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
        Schema::create('exam_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('cover');
            $table->integer('study_id');
            $table->integer('term_id');
            $table->integer('subject_id');
            $table->double('passingMarks',10,2);
            $table->double('negativeMarks',10,2);
            $table->date('startTime');
            $table->date('endTime');
            $table->string('examinerName');
            $table->string('examinerPhone');
            $table->string('examinerPosition');
            $table->integer('totalQuestions');
            $table->tinyInteger('haveNegative');
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
        Schema::dropIfExists('exam_list');
    }
};
