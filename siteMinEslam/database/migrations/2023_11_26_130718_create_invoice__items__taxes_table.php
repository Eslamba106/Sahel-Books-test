<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice__items__taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoice_item');
            $table->integer('invoice_item_id');
            $table->integer('tax_id');
            $table->integer('tax_type');
            $table->float('tax_rate');
            $table->float('tax_value');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice__items__taxes');
    }
}
