<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFields4 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->boolean('checkbox_trigger')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->dropColumn('checkbox_trigger');
        });
    }
}
