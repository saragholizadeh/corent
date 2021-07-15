<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->integer('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable(); //nullable for comments , with value for replies
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->longText('comment');
            $table->bigInteger('likes')->default(0);//likes count
            $table->bigInteger('dislikes')->default(0);//dislikes count
            $table->tinyInteger('status')->default(1);

            $table->foreign('parent_id')->references('id')->on('comments')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->string('created_at')->default(Carbon::now()->timestamp);
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
