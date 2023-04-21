<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamDivisionTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_division_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_division_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('announce')->nullable();
            $table->text('description')->nullable();
            $table->string('url');
            $table->boolean('visible')->default(true);
            $table->timestamps();

            $table->unique(['team_division_id', 'locale']);
            $table->foreign('team_division_id')->references('id')->on('team_division')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_division_translation');
    }
}
