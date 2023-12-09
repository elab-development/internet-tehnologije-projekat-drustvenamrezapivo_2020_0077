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
        Schema::create('likes', function (Blueprint $table) {
            $table->engine='InnoDB';
           
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('post_id');
        $table->unsignedBigInteger('liker_id');

       
        $table->primary(['user_id', 'post_id', 'liker_id']);

      
       $table->foreign(['user_id','post_id'])->references(['user_id','post_id'])->on('posts')->onDelete('cascade');
       
       $table->foreign('liker_id')->references('user_id')->on('users')->onDelete('cascade');
       

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
        Schema::dropIfExists('likes');
    }
};
