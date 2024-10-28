<?php
namespace Dibrovnik\Partnerprofile\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikPartnerprofileFieldValues extends Migration
{
    // public function up()
    // {
    //     Schema::table('dibrovnik_partnerprofile_field_values', function ($table) {
    //         $table->bigInteger('user_id')->nullable(false)->unsigned()->default(null)->comment(null)->change();
    //     });
    // }

    // public function down()
    // {
    //     Schema::table('dibrovnik_partnerprofile_field_values', function ($table) {
    //         $table->integer('user_id')->nullable(false)->unsigned()->default(null)->comment(null)->change();
    //     });
    // }
    public function up()
    {
        Schema::create('dibrovnik_partnerprofile_field_values', function ($table) {
            $table->increments('id'); // Первичный ключ
            $table->unsignedBigInteger('user_id'); // Поле для user_id
            $table->unsignedInteger('field_id'); // Поле для field_id
            $table->text('value')->nullable(); // Поле для хранения значения
            $table->timestamps(); // Для created_at и updated_at

            // Создаем внешний ключ для user_id, если таблица users существует
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Создаем внешний ключ для field_id
            $table->foreign('field_id')->references('id')->on('dibrovnik_partnerprofile_fields')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dibrovnik_partnerprofile_field_values');
    }
}