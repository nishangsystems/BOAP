<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProgramAdminsAddPromoter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(Schema::connection('mysql2')->hasColumn('program_admins', 'promoter'))
            return;
        Schema::connection('mysql2')->table('program_admins', function(Blueprint $table){
            $table->string('promoter')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
