<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofileTaxonomies extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_taxonomies', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_taxonomies');
    }
}
