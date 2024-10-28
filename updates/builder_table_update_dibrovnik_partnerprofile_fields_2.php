<?php
namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFields2 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function ($table) {
            $table->integer('sort_order')->default(0);
        });
    }

    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function ($table) {
            $table->dropColumn('sort_order');
        });
    }
}