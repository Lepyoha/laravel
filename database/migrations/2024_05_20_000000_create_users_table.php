<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_type_id')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('status')->default(\App\Enums\UserStatus::Active);
            $table->decimal('balance', 10, 2);
            $table->decimal('credit', 10, 2)->default(10);
            $table->string('confirmation_code')->nullable();
            $table->timestamp('last_delayed_payment')->nullable();
            $table->timestamp('freeze_start')->nullable();
            $table->timestamp('freeze_end')->nullable();
            $table->string('full_name');
            $table->date('birthday');
            $table->string('INN');
            $table->string('passport_series');
            $table->string('passport_number');
            $table->text('notes')->nullable();
            $table->integer('user_type')->default(0);
            $table->integer('city')->default(\App\Enums\UserCity::Donetsk);
            $table->string('street');
            $table->string('house_number');
            $table->string('apartment_number')->nullable();
            $table->integer('floor')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
