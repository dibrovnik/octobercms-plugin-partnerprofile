<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofilePartnerTaxonomy extends Migration
{
    public function up()
    {
        Schema::rename('dibrovnik_partnerprofile_', 'dibrovnik_partnerprofile_partner_taxonomy');
    }
    
    public function down()
    {
        Schema::rename('dibrovnik_partnerprofile_partner_taxonomy', 'dibrovnik_partnerprofile_');
    }
}
