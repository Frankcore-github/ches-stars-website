<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 20);
            $table->string('middle_name', 20)->nullable();
            $table->string('last_name', 20);
            $table->string('classes')->nullable();
            $table->string('address')->nullable();
            $table->string('phoneno')->nullable();
            $table->string('photo',30)->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
