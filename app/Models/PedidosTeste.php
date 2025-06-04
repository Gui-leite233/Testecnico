<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Test\CIUnitTestCase;
use App\Models\PedidosModel;

class PedidosTeste extends CIUnitTestCase
{
    public function testInsertAndFind()
    {
        $model = new PedidosModel();

        $dados = [
            'valor_total'  => 250.00,
            'valor_frete'  => 20.00,
            'data'         => '2025-06-04',
            'id_cliente'   => 1,
            'id_loja'      => 2,
            'id_situacao'  => 1
        ];

        $id = $model->insert($dados);
        $pedido = $model->find($id);

        $this->assertIsArray($pedido);
        $this->assertEquals(250.00, $pedido['valor_total']);
    }
}
