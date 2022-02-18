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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('emailVerifiedAt')->nullable();

            $table->string('password');
            $table->rememberToken();

            // After the CI/CD integration I must add default values on timestamp fields (https://stackoverflow.com/a/60814312/11578351)
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable()->useCurrentOnUpdate();

            $table->softDeletes('deletedAt');
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
};
