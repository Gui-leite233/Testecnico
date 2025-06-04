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
            $pedidoProcesso = $this->buscaPedido();

            $result = [];

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Processamento concluido',
                'pedidos_processamento' => count($result),
                'resultados' => $result
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erro no processamento: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Erro interno',
                'error' => $e->getMessage()
            ]);
        }
    }


    private function buscaPedido()
    {
        $builder = $this->pedidosModel->db->table('pedidos p');
        $builder->select('p.*, pp.*, c.nome as cliente_nome, c.cpf_cnpj, c.email, c.data_nasc')->join('pedidos_pagamentos pp', 'p.id = pp.id_pedido')->join('lojas_gateway lg', 'p.id_loja=pp.id_pedido')->join('clientes c', 'p.id_cliente=c.id')->where('ld.id_gateway', 1)->where('pp.id_formatopagto', 3)->where('p.id_situacao', 1)->where('pp.retorno_intermediador IS NULL');

        return $builder->get()->getResult();
    }


    private function processaPedido($pedido){
        try {
            $dadosClient = $this->preparaDados($pedido);
            $dadosPag = $this->preparaDadosPag($pedido, $dadosClient);
            $retornoGwy = $this->pagCompletoGateway->processaTransacao($dadosPag);
            $this->atualizaStatus($pedido, $retornoGwy);
            $this->salvaRetornoInter($pedido['id'], $retornoGwy);

            return [
                'pedido_id'=>$pedido['id'],
                'status'=>'processado',
                'retorno_gateway'=>$retornoGwy
            ];
        } catch (\Exception $e) {
             log_message('error', "Erro ao processar pedido {$pedido['id']}: " . $e->getMessage());
            
            return [
                'pedido_id' => $pedido['id'],
                'status' => 'erro',
                'erro' => $e->getMessage()
            ];
        }
    }


    private function preparaDados($pedido){
        return[
            'exeternal_id'=>(string)$pedido['id_cliente'],
            'name'=>$pedido['cliente_nome'],
            'type'=>'individual',
            'email'=>$pedido['email'],
            'document'=>[
                'type'=>'cpf',
                'number'=>$pedido['cpf_cpnj']
            ],
            'birthday'=>$pedido['data_nasc']
        ];
    }


    private function preparaDadosPag($pedido, $dadosClient) {
        $venc=$pedido['vencimento'];
        $vencFomtt = str_replace('-', '', $venc);

        return [
            'external_order_id' => $pedido['id'],
            'amount'=>(float)$pedido['valor_total'],
            'card_numer'=>$pedido['num_cartao'],
            'card_cvv'=>(string)$pedido['codigo_verificador'],
            'card_expiration_date'=>$vencFomtt,
            'card_holder_name'=>$pedido['nome_portador'],
            'customer'=>$dadosClient
        ];
    }

    private function atualizaStatus($pedido, $retornoGateway) {
        $novoStatus = 1;

        if(isset($retornoGateway['error']) && $retornoGateway['Error']==false){
            if(isset($retornoGateway['Transaction_code'])&&$retornoGateway['Transaction_code']==00){
                $novoStatus=2;//deu boa
            }
        } else{
            $novoStatus = 3;//deu ruim
        }

        $this->pedidosModel->update($pedido['id'], ['id_situacao'=>$novoStatus]);
    }


    private function salvaRetornoInter($pedido, $retornoGateway) {
        $retornoJson = json_encode($retornoGateway);
        $ProcessDate = date('Y-m-d H:i:s');

        $builder = $this->pedpagModel->db->table('pedidos_pagamentos');
        $builder->where('id_pedido', $pedido);
        $builder->update(['update_intermediador'=> $retornoJson, 'data_processamento'=>$ProcessDate]);
    }
}
