<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urequests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();

                $table->enum('experience', ['<1' , '1-3' , '3-5' , '>5']);
                $table->enum('request_type', ['author' , 'signal_seller']);
                $table->string('interested');
                $table->string('specialty');
                $table->text('bio');
                $table->tinyInteger('status')->default(0);

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urequests');
    }
}
