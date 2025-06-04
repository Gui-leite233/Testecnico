<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SdPedPag extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 103013,
                'id_pedido' => 98302,
                'id_formapagto' => 3,
                'qtd_parcelas' => 4,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => '5236387041984690',
                'nome_portador' => 'Elisa Adriana Barbosa',
                'codigo_verificacao' => 319,
                'vencimento' => '2022-08'
            ],
            [
                'id' => 103014,
                'id_pedido' => 98303,
                'id_formapagto' => 3,
                'qtd_parcelas' => 2,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => '5372472213342610',
                'nome_portador' => 'Renato Ryan',
                'codigo_verificacao' => 848,
                'vencimento' => '2022-03'
            ],
            [
                'id' => 103015,
                'id_pedido' => 98304,
                'id_formapagto' => 1,
                'qtd_parcelas' => 1,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => null,
                'nome_portador' => null,
                'codigo_verificacao' => null,
                'vencimento' => null
            ],
            [
                'id' => 103016,
                'id_pedido' => 98305,
                'id_formapagto' => 2,
                'qtd_parcelas' => 1,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => null,
                'nome_portador' => null,
                'codigo_verificacao' => null,
                'vencimento' => null
            ],
            [
                'id' => 103017,
                'id_pedido' => 98306,
                'id_formapagto' => 3,
                'qtd_parcelas' => 1,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => '4929521310619600',
                'nome_portador' => 'Raquel Moura',
                'codigo_verificacao' => 721,
                'vencimento' => '2023-03'
            ],
            [
                'id' => 103018,
                'id_pedido' => 98307,
                'id_formapagto' => 3,
                'qtd_parcelas' => 1,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => '4275824466404380',
                'nome_portador' => 'Fernando Julio',
                'codigo_verificacao' => 482,
                'vencimento' => '2022-05'
            ],
            [
                'id' => 103019,
                'id_pedido' => 98308,
                'id_formapagto' => 3,
                'qtd_parcelas' => 5,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => '5167913943407160',
                'nome_portador' => 'Kevin Pedro',
                'codigo_verificacao' => 441,
                'vencimento' => '2022-10'
            ],
            [
                'id' => 103020,
                'id_pedido' => 98309,
                'id_formapagto' => 2,
                'qtd_parcelas' => 1,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => null,
                'nome_portador' => null,
                'codigo_verificacao' => null,
                'vencimento' => null
            ],
            [
                'id' => 103021,
                'id_pedido' => 98310,
                'id_formapagto' => 1,
                'qtd_parcelas' => 1,
                'retorno_intermediador' => null,
                'data_processamento' => null,
                'num_cartao' => null,
                'nome_portador' => null,
                'codigo_verificacao' => null,
                'vencimento' => null
            ]
        ];
    }
}
