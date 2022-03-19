<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('email',50)->unique();
            // $table->string('password')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->default(0)->nullable();
            $table->string('google_token')->nullable();
            $table->tinyInteger('education')->nullable()->comment('dropdown values for education');
            $table->string('address')->nullable();
            $table->char('pin_code',12)->nullable();
            $table->Integer('country_id')->nullable();
            $table->Integer('state_id')->nullable();
            $table->Integer('city_id')->nullable();
            $table->string('profile_picture',50)->nullable();
            $table->enum('status',[0,1])->default(1)->comment('O represnet Inactive and 1 represents Active');
            $table->rememberToken()->nullable();
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
}
