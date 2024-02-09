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
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('reporter_id');


            $table->primary(['user_id', 'post_id', 'comment_id', 'reporter_id']);


            $table->foreign(['user_id', 'post_id', 'comment_id'])->references(['user_id', 'post_id', 'comment_id'])->on('comments')->onDelete('cascade');

            $table->foreign('reporter_id')->references('user_id')->on('users')->onDelete('cascade');


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
        Schema::dropIfExists('comment_reports');
    }
};
