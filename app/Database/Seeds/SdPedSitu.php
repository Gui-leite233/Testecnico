<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SdPedSitu extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'descricao' => 'Aguardando Pagamento'
            ],
            [
                'id' => 2,
                'descricao' => 'Pagamento Identificado'
            ],
            [
                'id' => 3,
                'descricao' => 'Pedido Cancelado'
            ]
        ];
        $this->db->table('pedido_situacao')->insertBatch($data);
    }
}
