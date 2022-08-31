<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('mode_of_payment');
            $table->float('loan_amount', 8, 2);
            $table->float('remaining_amount', 8, 2)->nullable();
            $table->integer('loan_duration');
            $table->string('processed_by')->nullable();
            $table->string('purpose')->nullable();
            $table->tinyInteger('loan_status')->default(0);
            $table->tinyInteger('closed_status')->default(0);
            $table->date('loan_closed_date')->default();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('loan_applications');
    }
}
