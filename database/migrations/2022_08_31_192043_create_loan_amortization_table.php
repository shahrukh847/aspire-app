<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanAmortizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_amortization', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->integer('user_id');
            $table->date('emi_date');
            $table->date('payment_date')->nullable();
            $table->float('emi', 8, 4);
            $table->integer('emi_order');
            $table->float('payment_amount', 8, 4)->nullable();
            $table->string('payment_status')->default('Unpaid');
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
        Schema::dropIfExists('loan_amortization');
    }
}
