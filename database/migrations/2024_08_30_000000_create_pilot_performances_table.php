<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilotPerformancesTable extends Migration
{
    public function up()
    {
        Schema::create('pilot_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilot_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('completed_deliveries')->default(0);
            $table->unsignedInteger('total_ratings')->default(0);
            $table->float('average_rating', 3, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pilot_performances');
    }
}