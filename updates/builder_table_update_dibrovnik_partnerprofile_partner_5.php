<?php namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofilePartner5 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->text('prices')->nullable()->unsigned(false)->default(null)->comment(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_partner', function($table)
        {
            $table->string('prices', 255)->nullable()->unsigned(false)->default(null)->comment(null)->change();
        });
    }
}
