<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rests', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start')->nullable(); // 休憩開始時間、未設定可能
            $table->dateTime('stop')->nullable();  // 休憩終了時間、未設定可能
            $table->integer('total')->default(0); // 休憩の総時間を分で保存
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
        Schema::table('rests', function (Blueprint $table) {
            $table->dropColumn('total');
        });
    }
}
