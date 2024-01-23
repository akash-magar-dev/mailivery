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
        Schema::create('sent_mail', function (Blueprint $table) {
            $table->id();
            $table->string('to_recipient');
            $table->string('cc_recipient')->nullable();
            $table->string('subject');
            $table->longText('message');
            $table->string('attachment')->nullable();
            $table->longText('tracking_id')->nullable();
            $table->longText('tracking_info')->nullable();
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
        Schema::dropIfExists('sent_mail');
    }
};

