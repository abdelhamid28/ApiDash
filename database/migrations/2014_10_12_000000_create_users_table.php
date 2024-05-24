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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('enroll')->nullable();
            $table->integer('term_id');
            $table->integer('study_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->tinyInteger('gender')->default(4);
            $table->tinyInteger('type')->default(1);
            $table->string('cover')->nullable();
            $table->string('country_code');
            $table->string('mobile');
            $table->tinyInteger('verified')->default(0);
            $table->text('fcm_token')->nullable();
            $table->text('others')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('extra_field')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
