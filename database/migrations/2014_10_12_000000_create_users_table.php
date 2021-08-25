<?php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            $table->string('name' , 45)->nullable();
            $table->string('username' , 45)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('location')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();

            //wallet adresses
            $table->string('bitcoin')->nullable();
            $table->string('ethereum')->nullable();
            $table->string('tether')->nullable();
            $table->string('bnb')->nullable();

            //status 0 , 1 for ban and unban user
            $table->tinyInteger('stack_status')->default(1);
            $table->tinyInteger('main_status')->default(1);

            $table->enum('main_level' , ['admin', 'author' , 'user' ,'customer' , 'signal_seller'])->default('user');
            $table->enum('stack_level' , ['admin','newcomer','active','experienced','expert','specialist','professor','master'])->default('newcomer');

            $table->integer('stars')->default(0);

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
