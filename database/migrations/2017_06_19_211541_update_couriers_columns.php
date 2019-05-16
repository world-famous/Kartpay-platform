<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCouriersColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('couriers', function (Blueprint $table)
        {
            $table->string('default_language')->after('optional_fields');
            $table->string('support_languages')->after('default_language');
            $table->string('service_from_country_iso3')->after('support_languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('couriers', function (Blueprint $table)
        {
            if (Schema::hasTable('couriers'))
            {
                Schema::table('couriers', function (Blueprint $table)
                {
                    $table->dropColumn('default_language');
                    $table->dropColumn('support_languages');
                    $table->dropColumn('service_from_country_iso3');
                });
            }
        });
    }
}
