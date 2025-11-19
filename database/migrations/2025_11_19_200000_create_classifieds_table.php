<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('classifieds')) {
            Schema::create('classifieds', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('author')->nullable();
                $table->string('title');
                $table->text('body')->nullable();
                $table->string('source_url')->nullable();
                $table->boolean('is_sponsored')->default(false);
                $table->integer('comments')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('classifieds');
    }
};
