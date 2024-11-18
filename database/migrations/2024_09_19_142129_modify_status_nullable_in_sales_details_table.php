<?php
 

 use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyStatusInSalesDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            // Modify the 'status' column to NOT be nullable and have a default value of 'active'
            $table->string('status')->default('active')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            // Revert the 'status' column to nullable and remove the default value
            $table->string('status')->nullable()->default(null)->change();
        });
    }
}
