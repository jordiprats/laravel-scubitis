<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('promo_codes', function (Blueprint $table) {
        $table->increments('id');
        $table->string('website');
        $table->string('promo_id');
        $table->unique(['website', 'promo_id'], 'promo_unique_per_website');
        $table->integer('discount')->nullable();
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
    Schema::dropIfExists('promo_codes');
  }
}
