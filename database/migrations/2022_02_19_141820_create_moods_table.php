a<?php

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
            Schema::create('moods', function (Blueprint $table) {
                $table->uuid('id')->primary();

                $table->string('name');
                $table->string('description')->nullable();

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
            Schema::dropIfExists('moods');
        }
    };
