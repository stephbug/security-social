<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLogTable extends Migration
{

    public function up(): void
    {
        Schema::create('social_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('social_id',100)->primary();
            $table->string('social_user_id',100);
            $table->string('social_provider_name',50);
            $table->string('social_user_email',150);
            $table->string('access_token',150);
            $table->string('secret_token',150);
            $table->json('information');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_users');
    }
}
