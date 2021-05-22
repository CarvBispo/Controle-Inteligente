<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('origin');
            $table->date('reference_month');
            $table->date('expiration_at');
            $table->date('payment_at');
            $table->decimal('amount');
            $table->decimal('amount_tax');
            $table->decimal('amount_paid');
            $table->unsignedInteger('measure_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->string('file')->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('measure_id')->references('id')->on('entities');
            $table->foreign('updated_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
