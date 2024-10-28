<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofilePartner7 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->boolean('account_verificated')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->dropColumn('account_verificated');
        });
    }
}
