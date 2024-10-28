<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFields extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->text('options')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->dropColumn('options');
        });
    }
}
