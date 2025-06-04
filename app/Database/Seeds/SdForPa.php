<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SdForPa extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'descricao' => 'Boleto Bancário'
            ],
            [
                'id' => 2,
                'descricao' => 'Depósito Bancário'
            ],
            [
                'id' => 3,
                'descricao' => 'Cartão de Crédito'
            ]
        ];
        $this->db->table('FormasPagamento')->insertBatch($data);

    }
}
