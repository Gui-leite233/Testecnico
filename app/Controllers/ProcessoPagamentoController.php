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

            return $this->response->setStatusCode($resultado['status'])->setJSON($resultado['data']);

        } catch (\Exception $e) {
            log_message('error', 'Erro no processamento: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'Error' => true,
                'Transaction_code' => '99',
                'Message' => 'Erro interno do servidor'
            ]);
        }
    }


    private function buscaPedido()
    {
        $builder = $this->pedidosModel->db->table('pedidos p');
        $builder->select('p.*, pp.*, c.nome as cliente_nome, c.cpf_cnpj, c.email, c.data_nasc')->join('pedidos_pagamentos pp', 'p.id = pp.id_pedido')->join('lojas_gateway lg', 'p.id_loja = lg.id_loja')->join('clientes c', 'p.id_cliente=c.id')->where('lg.id_gateway', 1)->where('pp.id_formatopagto', 3)->where('p.id_situacao', 1)->where('pp.retorno_intermediador IS NULL');

        return $builder->get()->getResultArray();
    }


    private function processaPedido($pedido)
    {
        try {
            $dadosClient = $this->preparaDados($pedido);
            $dadosPag = $this->preparaDadosPag($pedido, $dadosClient);
            $retornoGwy = $this->pagCompletoGateway->processaTransacao($dadosPag);
            $this->atualizaStatus($pedido, $retornoGwy);
            $this->salvaRetornoInter($pedido['id'], $retornoGwy);

            return [
                'pedido_id' => $pedido['id'],
                'status' => 'processado',
                'retorno_gateway' => $retornoGwy
            ];
        } catch (\Exception $e) {
            //log_message('info', "Erro ao processar pedido {$pedido['id']}: " . $e->getMessage());

            return [
                'pedido_id' => $pedido['id'],
                'status' => 'erro',
                'erro' => $e->getMessage()
            ];
        }
    }


    private function preparaDados($pedido)
    {
        return [
            'external_id' => (string) $pedido['id_cliente'],
            'name' => $pedido['cliente_nome'],
            'type' => 'individual',
            'email' => $pedido['email'],
            'documents' => [
                'type' => 'cpf',
                'number' => $pedido['cpf_cnpj']
            ],
            'birthday' => $pedido['data_nasc']
        ];
    }


    private function preparaDadosPag(array $pedido, array $dadosClient)
    {
        $amount = (float) $pedido['valor_total'];

        $venc = $pedido['vencimento'];
        $mes = date('m', strtotime($venc));
        $ano2 = date('y', strtotime($venc));
        $expiration = $mes . $ano2;


        $customer = [
            'external_id' => (string) $dadosClient['external_id'],
            'name' => (string) $dadosClient['name'],
            'type' => (string) $dadosClient['type'],
            'email' => (string) $dadosClient['email'],
            'documents' => [
                [
                    'type' => (string) $dadosClient['documents']['type'],
                    'number' => (string) $dadosClient['documents']['number'],
                ]
            ],
            'birthday' => (string) $dadosClient['birthday'],
        ];

        return [
            'external_order_id' => (int) $pedido['id'],
            'amount' => (float) $amount,
            'card_number' => (string) $pedido['num_cartao'],
            'card_cvv' => (string) $pedido['codigo_verificacao'],
            'card_expiration_date' => (string) $expiration,
            'card_holder_name' => (string) $pedido['nome_portador'],
            'customer' => $customer,
        ];
    }

    private function atualizaStatus($pedido, $retornoGateway)
    {
        $novoStatus = 1;

        if (isset($retornoGateway['info']) && $retornoGateway['Error'] == false) {
            if (isset($retornoGateway['Transaction_code']) && $retornoGateway['Transaction_code'] == 00) {
                $novoStatus = 2;//deu boa
            }
        } else {
            $novoStatus = 3;//deu ruim
        }

        $this->pedidosModel->update($pedido['id'], ['id_situacao' => $novoStatus]);
    }


    private function salvaRetornoInter($pedido, $retornoGateway)
    {
        $retornoJson = json_encode($retornoGateway);
        $ProcessDate = date('Y-m-d H:i:s');

        $builder = $this->pedpagModel->db->table('pedidos_pagamentos');
        $builder->where('id_pedido', $pedido);
        $builder->update(['retorno_intermediador' => $retornoJson, 'data_processamento' => $ProcessDate]);
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

            $result = $this->pagCompletoGateway->processaTransacao($dados);

            return [
                'status' => 200,
                'data' => [
                    'Error' => $result['Error'] ?? $result['error'] ?? true,
                    'Transaction_code' => $result['Transaction_code'] ?? $result['code'] ?? '99',
                    'Message' => $result['message'] ?? 'Erro desconhecido',
                ]
            ];
        } catch (\Exception $e) {
            log_message('error', 'Erro no processamento: ' . $e->getMessage());

            return [
                'status' => 500,
                'data' => [
                    'Error' => true,
                    'Transaction_code' => '99',
                    'Message' => 'Erro interno'
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
