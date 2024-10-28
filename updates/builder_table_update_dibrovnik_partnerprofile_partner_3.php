<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofilePartner3 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->string('avatar', 255)->nullable()->change();
            $table->string('tariff', 255)->default('0')->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->string('avatar', 255)->nullable(false)->change();
            $table->string('tariff', 255)->default(null)->change();
        });
    }
}
