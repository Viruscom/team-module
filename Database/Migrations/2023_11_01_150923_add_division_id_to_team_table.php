<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddDivisionIdToTeamTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('team', function (Blueprint $table) {
                $table->unsignedBigInteger('division_id')->nullable();
                $table->foreign('division_id')
                    ->references('id')
                    ->on('team_division')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('team', function (Blueprint $table) {
                $table->dropColumn('division_id');
            });
        }
    }
