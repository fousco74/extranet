<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    public function up()
    {
        Schema::create('one_drive_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->timestamps();
        });

        // Pivot table for many-to-many relationship
        Schema::create('one_drive_link_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('one_drive_link_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('one_drive_link_user');
        Schema::dropIfExists('one_drive_links');
    }
};