<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulations', function (Blueprint $table) {
            $table->id();

            $table->string('country');
            $table->string('short_description');
            $table->longText('description');
            $table->bigInteger('population');
            $table->bigInteger('area');
            $table->bigInteger('internet_penetration');
            $table->string('national_currency');
            $table->string('goverment');
            $table->string('president');
            $table->string('capital');
            $table->string('language');
            $table->float('economic_growth');
            $table->string('dgtl_curr_lgs')
            ->comment('Legislation of digital currencies');

            $table->string('dgtl_curr_tax')
            ->comment('Tax on digital currencies');

            $table->string('dgtl_curr_pymt')
            ->comment('Payment status through digital currency');

            $table->string('dgtl_curr_ntiol')
            ->comment('National Digital Currency');

            $table->string('ICO');

            $table->string('crpto_antimon_rules')
            ->comment('has Anti-money laundering rules for crypto or not');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('regulations');
    }
}
