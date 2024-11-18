<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToVariableListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variable_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->after('Description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variable_lists', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
