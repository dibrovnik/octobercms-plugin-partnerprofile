<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofileFields extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_fields', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('label', 255);
            $table->string('type', 50);
            $table->boolean('required');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_fields');
    }
}