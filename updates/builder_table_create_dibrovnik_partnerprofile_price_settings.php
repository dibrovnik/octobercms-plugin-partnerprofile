<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofilePriceSettings extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_price_settings', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('currency_options');
            $table->string('value_options');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_price_settings');
    }
}
