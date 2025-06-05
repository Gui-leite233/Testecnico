<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SdGateWays extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'descricao' => 'PAGCOMPLETO',
                'endpoint' => 'https://api11.ecompleto.com.br/'
            ],
            [
                'id' => 2,
                'descricao' => 'CIELO',
                'endpoint' => 'https://api.cielo.com.br/v1/transactions/'
            ],
            [
                'id' => 3,
                'descricao' => 'PAGSEGURO',
                'endpoint' => 'https://api.pagseguro.com.br/transactions/'
            ]
        ];
        $this->db->table('gateways')->insertBatch($data);
    }
}
