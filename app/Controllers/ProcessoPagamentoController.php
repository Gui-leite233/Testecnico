<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PedidosModel;
use App\Models\LojasGateway;
use App\Models\PedPagModel;
use App\Libraries\PagCompletoGateway;

class ProcessoPagamentoController extends BaseController
{
    protected $pedidosModel;
    protected $pedpagModel;
    protected $lojasGatewayModel;
    protected $pagCompletoGateway;


    public function __construct()
    {
        $this->pedidosModel = new PedidosModel();
        $this->pedpagModel = new PedPagModel();
        $this->lojasGatewayModel = new LojasGateway();
        $this->pagCompletoGateway = new PagCompletoGateway();
    }

    public function index()
    {

        try {
            $dadosPost = $this->request->getJSON(true);


            if (empty($dadosPost)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'Error' => true,
                    'Message' => 'Dados não fornecidos'
                ]);
            }

            $resultado = $this->processaTransacao($dadosPost);


            return $this->response->setJSON($resultado);


        } catch (\Exception $e) {
            log_message('error', 'Erro no processamento: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'Error' => true,
                'Transaction_code' => '99',
                'Message' => 'Erro interno do servidor'
            ]);
        }
    }


    private function buscaPedido($pedido_id)
    {
        $builder = $this->pedpagModel->db->table('pedidos_pagamentos pp');

        $builder->select('
        pp.id as pedido_pagamento_id,
        p.id as pedido_id,
        fp.id as formapagto_id,
        ps.id as pedido_situacao_id,
        lg.id_loja as loja_id,
        g.id as gateway_id');
        $builder->join('pedidos p', 'pp.id_pedido = p.id');
        $builder->join('formas_pagamento fp', 'pp.id_formatopagto = fp.id');
        $builder->join('pedido_situacao ps', 'p.id_situacao = ps.id');
        $builder->join('lojas_gateway lg', 'p.id_loja = lg.id_loja');
        $builder->join('gateways g', 'lg.id_gateway = g.id');
        $builder->where('p.id', $pedido_id);
        $builder->where('fp.id', 3);
        $builder->where('ps.id', 1);
        $builder->where('g.id', 1);

        $query = $builder->get();
        $result = $query->getResultArray();

        return $result;
    }








    private function atualizaStatus($pedido, $retornoGateway)
    {


        switch ($retornoGateway['Transaction_code']) {
            case 00:
                $builder = $this->pedpagModel->db->table('pedidos_pagamentos');
                $builder->where('id_pedido', $pedido);
                $builder->update(['retorno_intermediador' => 2]);
                break;
            case 03:
            case 04:
                $builder = $this->pedpagModel->db->table('pedidos_pagamentos');
                $builder->where('id_pedido', $pedido);
                $builder->update(['retorno_intermediador' => 3]);
                break;


        }


    }

    private function processaTransacao($dados)
    {
        try {
            if (empty($dados)) {
                return [
                    'status' => 401,
                    'data' => [
                        'Error' => true,
                        'Transaction_code' => '99',
                        'Message' => 'Dados não fornecidos'
                    ]
                ];
            }

            $validate = $this->validaDados($dados);
            if ($validate !== true) {
                return [
                    'status' => 400,
                    'data' => $validate
                ];
            }
            $dbData = $this->buscaPedido($dados['external_order_id']);
            if (count($dbData) > 0) {
                $result = $this->pagCompletoGateway->transacao($dados);
                $this->atualizaStatus($dados['external_order_id'], $result);
                return $result;
            }


        } catch (\Exception $e) {
            log_message('error', 'Erro no processamento: ' . $e->getMessage());

            return [
                'status' => 500,
                'data' => [
                    'Error' => true,
                    'Transaction_code' => '99',
                    'Message' => 'Erro interno do servidor'
                ]
            ];
        }
    }

    private function validaDados($dados)
    {
        $Obg = [
            'external_order_id',
            'amount',
            'card_number',
            'card_cvv',
            'card_expiration_date',
            'card_holder_name',
            'customer'
        ];

        foreach ($Obg as $campo) {
            if (!isset($dados[$campo])) {
                return [
                    'Error' => true,
                    'Transaction_code' => '99',
                    'Message' => "Campo obrigatório: $campo"
                ];
            }
        }


        if (!isset($dados['customer']['external_id']) || !isset($dados['customer']['name']) || !isset($dados['customer']['email']) || !isset($dados['customer']['documents'])) {
            return [
                'Error' => true,
                'Transaction_code' => '99',
                'Message' => 'Dados incompletos'
            ];

        }
        return true;
    }


}
