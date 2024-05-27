<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('borrowed_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books');
            $table->foreign('user_id')->references('id')->on('users');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('borrows');

        DB::statement('DROP TRIGGER IF EXISTS set_returned_at');
    }
};
