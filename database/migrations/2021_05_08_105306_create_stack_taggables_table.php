<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateStackTaggablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stack_taggables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stack_tag_id');
            $table->integer('stack_taggable_id');
            $table->string('stack_taggable_type');
            $table->string('created_at')->default(Carbon::now()->timestamp);        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stack_taggables');
    }
}
