<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofilePartnerTaxonomy extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_partner_taxonomy', function($table)
        {
            $table->integer('partner_id')->unsigned();
            $table->smallInteger('taxonimy_option_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_partner_taxonomy');
    }
}
