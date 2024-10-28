<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileTaxonomyOptions extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_taxonomy_options', function($table)
        {
            $table->integer('taxonomy_id')->nullable(false)->unsigned()->default(null)->comment(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_taxonomy_options', function($table)
        {
            $table->bigInteger('taxonomy_id')->nullable(false)->unsigned()->default(null)->comment(null)->change();
        });
    }
}
