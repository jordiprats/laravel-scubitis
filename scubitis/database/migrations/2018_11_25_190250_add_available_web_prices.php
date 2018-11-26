<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvailableWebPrices extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('web_prices', function (Blueprint $table) {
      $table->boolean('available')->default(true);
      $table->decimal('price', 5, 2)->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('web_prices', function (Blueprint $table) {
      $table->dropColumn('available');
      $table->decimal('price', 5, 2)->nullable(false)->change();
    });
  }
}
