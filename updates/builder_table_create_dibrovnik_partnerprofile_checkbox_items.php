<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofileCheckboxItems extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_checkbox_items', function($table)
        {
            $table->increments('id')->unsigned();
            $table->bigInteger('field_id')->unsigned();
            $table->text('checkbox_item_text')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_checkbox_items');
    }
}
