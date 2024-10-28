<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileTaxonomyOptions2 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_taxonomy_options', function($table)
        {
            $table->string('slug', 255)->change();
            $table->foreign('taxonomy_id')->references('id')->on('dibrovnik_partnerprofile_taxonomies')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_taxonomy_options', function($table)
        {
            $table->string('slug', 254)->change();
        });
    }
}
