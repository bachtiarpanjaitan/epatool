<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->nullable()->index();
            $table->integer('user_id')->nullable()->index();
            $table->integer('feedback_type')->nullable()->index();
            $table->text('feedback')->nullable();
            $table->integer('status')->nullable()->index();
            $table->integer('is_read')->nullable()->index();
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
        Schema::dropIfExists('request_feedbacks');
    }
}
