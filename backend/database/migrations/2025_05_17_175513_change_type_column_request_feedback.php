<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeColumnRequestFeedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_feedbacks', function (Blueprint $table) {
            $table->string('feedback_type')->change();
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_feedbacks', function (Blueprint $table) {
            $table->integer('feedback_type')->change(); 
            $table->integer('status')->change(); 
        });
    }
}
