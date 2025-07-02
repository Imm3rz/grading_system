<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('grades', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('student_id');
    $table->string('subject');
    $table->decimal('grade', 5, 2);
    $table->timestamps();

    $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
});

}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
