<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
           $table->id();
            $table->dateTime('start')->nullable(); // 開始時間、未設定可能
            $table->dateTime('stop')->nullable();  // 終了時間、未設定可能
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // ユーザーID
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
        Schema::dropIfExists('works');
    }
}
