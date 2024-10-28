<?php
namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFields3 extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_categories', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('dibrovnik_partnerprofile_fields', function ($table) {
            $table->integer('category_id')->nullable()->unsigned();
            $table->foreign('category_id')->references('id')->on('dibrovnik_partnerprofile_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('dibrovnik_partnerprofile_fields', function ($table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('dibrovnik_partnerprofile_categories');
    }
}