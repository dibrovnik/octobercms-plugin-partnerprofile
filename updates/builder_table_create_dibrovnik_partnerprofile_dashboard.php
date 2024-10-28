<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikPartnerprofileDashboard extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_dashboard', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('link')->nullable();
            $table->string('name')->nullable();
            $table->integer('sort_order')->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_dashboard');
    }
}
