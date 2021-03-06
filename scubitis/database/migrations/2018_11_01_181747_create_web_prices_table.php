<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebPricesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('web_prices', function (Blueprint $table) {
      $table->increments('id');
      $table->string('url');
      $table->string('website');
      $table->timestamp('data');
      $table->unique(['url', 'data'], 'url_data_unique');
      $table->decimal('price', 5, 2);
      $table->string('currency')->default('EUR');
      $table->integer('product_id')->references('id')->on('products');
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
    Schema::dropIfExists('web_prices');
  }
}
