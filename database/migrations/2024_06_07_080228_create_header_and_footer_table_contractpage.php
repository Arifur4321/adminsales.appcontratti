<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headerAndFooterTableContractpage', function (Blueprint $table) {
            $table->id(); // Adds an auto-incrementing primary key column
            $table->unsignedBigInteger('contractID'); // Foreign key for contract
            $table->unsignedBigInteger('HeaderID')->nullable(); // Foreign key for header/footer
            $table->string('HeaderPage')->nullable(); // Column to determine 'First' or 'EveryPage'
            $table->unsignedBigInteger('FooterID')->nullable();
            $table->string('FooterPage')->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('headerAndFooterTableContractpage');
    }
};
