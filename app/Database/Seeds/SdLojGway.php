<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SdLojGway extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'id_loja' => 90,
                'id_gateway' => 1
            ],
            [
                'id' => 2,
                'id_loja' => 92,
                'id_gateway' => 2
            ],
            [
                'id' => 3,
                'id_loja' => 115,
                'id_gateway' => 1
            ],
            [
                'id' => 4,
                'id_loja' => 98,
                'id_gateway' => 1
            ],
            [
                'id' => 5,
                'id_loja' => 97,
                'id_gateway' => 1
            ],
            [
                'id' => 6,
                'id_loja' => 94,
                'id_gateway' => 1
            ]
        ];
        $this->db->table('lojas_gateway')->insertBatch($data);
    }
}
