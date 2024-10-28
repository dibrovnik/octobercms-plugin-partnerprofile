<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofile extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_', function($table)
        {
            $table->integer('partner_id')->unsigned();
            $table->integer('taxonomy_option_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_');
    }
}
