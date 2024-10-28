<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofilePartner extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('avatar');
            $table->string('tariff');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_partner');
    }
}
