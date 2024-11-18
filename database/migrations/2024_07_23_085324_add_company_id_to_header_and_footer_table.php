<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToHeaderAndFooterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('header_and_footer', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('editor_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('header_and_footer', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
