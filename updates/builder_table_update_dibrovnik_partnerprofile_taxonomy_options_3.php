<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileTaxonomyOptions3 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_taxonomy_options', function($table)
        {
            $table->string('name', 255)->nullable()->change();
            $table->string('slug', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_taxonomy_options', function($table)
        {
            $table->string('name', 255)->nullable(false)->change();
            $table->string('slug', 255)->nullable(false)->change();
        });
    }
}
