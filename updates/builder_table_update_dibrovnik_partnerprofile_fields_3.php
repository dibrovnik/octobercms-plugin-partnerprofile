<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFields3 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->string('name');
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->dropColumn('name');
        });
    }
}
