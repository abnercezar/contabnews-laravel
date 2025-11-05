<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('upvotes_count')->default(0)->after('time');
            $table->unsignedInteger('downvotes_count')->default(0)->after('upvotes_count');
        });

        // preenche contadores a partir das reações se a tabela existir
        if (Schema::hasTable('reactions')) {
            $posts = \DB::table('reactions')
                ->select('post_id', \DB::raw("SUM(CASE WHEN type='up' THEN 1 ELSE 0 END) as up_count"), \DB::raw("SUM(CASE WHEN type='down' THEN 1 ELSE 0 END) as down_count"))
                ->groupBy('post_id')
                ->get();

            foreach ($posts as $p) {
                \DB::table('posts')->where('id', $p->post_id)->update([
                    'upvotes_count' => $p->up_count,
                    'downvotes_count' => $p->down_count,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['upvotes_count', 'downvotes_count']);
        });
    }
};
