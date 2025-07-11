<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentContactToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('students', function (Blueprint $table) {
        $table->string('parent_email')->nullable();
        $table->string('parent_phone')->nullable(); // Optional: for SMS
    });
}

public function down()
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn(['parent_email', 'parent_phone']);
    });
}

}
