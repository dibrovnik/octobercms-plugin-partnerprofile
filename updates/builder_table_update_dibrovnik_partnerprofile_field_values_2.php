<?php
namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFieldValues2 extends Migration
{
    public function up()
    {
        // Schema::table('dibrovnik_partnerprofile_field_values', function($table)
        // {
        //     $table->integer('field_id')->unsigned()->change();
        // });
        Schema::table('dibrovnik_partnerprofile_field_values', function ($table) {
            $table->foreign('field_id')->references('id')->on('dibrovnik_partnerprofile_fields');
        });

    }

    public function down()
    {
        // Schema::table('dibrovnik_partnerprofile_field_values', function ($table) {
        //     $table->integer('field_id')->unsigned(false)->change();

        // });
        Schema::table('dibrovnik_partnerprofile_field_values', function ($table) {
            $table->dropForeign(['field_id']);
        });

    }
}