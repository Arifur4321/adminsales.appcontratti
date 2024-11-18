<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToSalesDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            $table->string('status')->nullable()->after('company_id'); // Adds status with nullable option
        });
    }

    public function down()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
