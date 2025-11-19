<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ClassifiedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $items = [
            [
                'user_id' => null,
                'author' => 'Anúncios Gerais',
                'title' => 'Vendo mesa de escritório em bom estado',
                'body' => 'Mesa de 1,5m, cor nogueira. Pouco usada, sem avarias.',
                'content' => 'Mesa de 1,5m, cor nogueira. Pouco usada, sem avarias.',
                'source_url' => null,
                'is_sponsored' => false,
                'comments' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => null,
                'author' => 'Loja Tech',
                'title' => 'Venda de smartphone X novo lacrado',
                'body' => 'Smartphone X 128GB, lacrado, garantia de 12 meses.',
                'content' => 'Smartphone X 128GB, lacrado, garantia de 12 meses.',
                'source_url' => null,
                'is_sponsored' => true,
                'comments' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => null,
                'author' => 'Particular',
                'title' => 'Procuro eletrodomésticos usados',
                'body' => 'Interessado em geladeiras e fogões em bom estado. Retiro em São Paulo.',
                'content' => 'Interessado em geladeiras e fogões em bom estado. Retiro em São Paulo.',
                'source_url' => null,
                'is_sponsored' => false,
                'comments' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => null,
                'author' => 'Serviços Rápidos',
                'title' => 'Aulas particulares de matemática',
                'body' => 'Aulas online e presenciais. Professor com experiência em concurso.',
                'content' => 'Aulas online e presenciais. Professor com experiência em concurso.',
                'source_url' => null,
                'is_sponsored' => false,
                'comments' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('classifieds')->insert($items);
    }
}
