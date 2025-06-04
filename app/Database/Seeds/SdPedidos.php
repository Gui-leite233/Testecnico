<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SdPedidos extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 98302,
                'valor_total' => 250.74,
                'valor_frete' => 33.4,
                'data' => '2021-08-20 00:00:00',
                'id_cliente' => 8796,
                'id_loja' => 90,
                'id_situacao' => 1
            ],
            [
                'id' => 98303,
                'valor_total' => 583.92,
                'valor_frete' => 57.85,
                'data' => '2021-08-23 00:00:00',
                'id_cliente' => 5789,
                'id_loja' => 92,
                'id_situacao' => 1
            ],
            [
                'id' => 98304,
                'valor_total' => 97.25,
                'valor_frete' => 17.5,
                'data' => '2021-08-23 00:00:00',
                'id_cliente' => 6748,
                'id_loja' => 90,
                'id_situacao' => 2
            ],
            [
                'id' => 98305,
                'valor_total' => 66.89,
                'valor_frete' => 22.55,
                'data' => '2021-08-25 00:00:00',
                'id_cliente' => 6872,
                'id_loja' => 115,
                'id_situacao' => 2
            ],
            [
                'id' => 98306,
                'valor_total' => 115.9,
                'valor_frete' => 19.5,
                'data' => '2021-08-25 00:00:00',
                'id_cliente' => 6716,
                'id_loja' => 98,
                'id_situacao' => 1
            ],
            [
                'id' => 98307,
                'valor_total' => 153.72,
                'valor_frete' => 25.5,
                'data' => '2021-08-25 00:00:00',
                'id_cliente' => 4802,
                'id_loja' => 97,
                'id_situacao' => 1
            ],
            [
                'id' => 98308,
                'valor_total' => 87.9,
                'valor_frete' => 13.5,
                'data' => '2021-08-26 00:00:00',
                'id_cliente' => 9484,
                'id_loja' => 94,
                'id_situacao' => 1
            ],
            [
                'id' => 98309,
                'valor_total' => 223.9,
                'valor_frete' => 28.75,
                'data' => '2021-08-27 00:00:00',
                'id_cliente' => 1830,
                'id_loja' => 90,
                'id_situacao' => 2
            ],
            [
                'id' => 98310,
                'valor_total' => 58.9,
                'valor_frete' => 19.85,
                'data' => '2021-08-27 00:00:00',
                'id_cliente' => 2280,
                'id_loja' => 92,
                'id_situacao' => 1
            ]
        ];
        $this->db->table('Pedidos')->insertBatch($data);

    }
}
