<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFields5 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->integer('taxonomy_id')->nullable()->unsigned();
            $table->string('taxonomy_style')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->dropColumn('taxonomy_id');
            $table->dropColumn('taxonomy_style');
        });
    }
}
