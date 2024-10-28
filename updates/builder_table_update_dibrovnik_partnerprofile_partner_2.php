<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofilePartner2 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->bigInteger('user_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->dropColumn('user_id');
        });
    }
}
