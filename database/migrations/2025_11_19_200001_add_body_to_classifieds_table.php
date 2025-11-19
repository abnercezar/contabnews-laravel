<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('classifieds') && ! Schema::hasColumn('classifieds', 'body')) {
            Schema::table('classifieds', function (Blueprint $table) {
                $table->text('body')->nullable()->after('title');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('classifieds') && Schema::hasColumn('classifieds', 'body')) {
            Schema::table('classifieds', function (Blueprint $table) {
                $table->dropColumn('body');
            });
        }
    }
};
