<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            $table->string('status')->default('active')->nullable(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('sales_details', function (Blueprint $table) {
            $table->string('status')->nullable()->default(null)->change();
        });
    }
    
};