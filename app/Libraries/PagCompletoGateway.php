<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use finfo;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\New_;

class PagCompletoGateway
{
    private $baseURL;
    private $endpoint;
    private $accessToken;
    private $timeout;


    public function __construct()
    {
        $this->baseURL = 'https://apiinterna.ecompleto.com.br';
        $this->endpoint = '/exams/processTransaction';
        $this->accessToken = env('PAGCOMPLETO_ACCESS_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjI2ODQsInN0b3JlSWQiOjE5NzksImlhdCI6MTc0ODU0NTIwNiwiZXhwIjoxNzQ5NDA5MjA2fQ.pngOSO40bI67Q1bCwWc_SFdIuBRhDQKww2DyxfhTKqo');
        $this->timeout = 30;
    }


    public function transacao(array $dadosPagamento)
    {
        try {
            $this->validateDadosTr($dadosPagamento);

            $url = $this->baseURL . $this->endpoint . '?accesstoken=' . $this->accessToken;

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($dadosPagamento),
                CURLOPT_HTTPHEADER => [
                    'content-Type: application/json',
                    'Accept: application/json'
                ],
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2
            ]);


            $response = curl_exec($curl);
            $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                throw new \Exception("Erro: " . $error);
            }

            if ($http != 200) {
                throw new \Exception("Gateway retorno: " . $http);
            }

            $retorno = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Erro na resposta do gateway: " . json_last_error_msg());
            }

            $this->logTransacao($dadosPagamento, $retorno);

            return $retorno;
        } catch (\Exception $e) {
            log_message('error', 'Erro PagCompletoGateway:' . $e->getMessage());

            return [
                'Error' => true,
                'Transaction_code' => '99',
                'Message' => 'Erro na comunicacao: ' . $e->getMessage()
            ];
        }
    }


    private function validateDadosTr(array $dados)
    {
        $obg = [
            'external_order_id',
            'amount',
            'card_number',
            'card_cvv',
            'card_expiration_date',
            'card_holder_name',
            'customer'
        ];

        foreach ($obg as $campo) {
            if (!isset($dados[$campo]) || empty($dados[$campo])) {
                throw new \Exception("campo obrigatoroio: " . $campo);
            }
        }

        if (!isset($dados['customer']['external_id']) || !isset($dados['customer']['name']) || !isset($dados['customer']['email']) || !isset($dados['customer']['documents'])) {
            throw new \Exception("dados incompletos");

        }

        if (!is_numeric($dados['amount']) || $dados['amount'] <= 0) {
            throw new \Exception("Valor inválido");
        }

        if (!$this->validateNum($dados['card_number'])) {
            throw new \Exception("Número inválido");
        }

        if (!preg_match('/^\d{3,4}$/', $dados['card_cvv'])) {
            throw new \Exception("cvv invalido");
        }

        if (!$this->validateDataVen($dados['card_expiration_date'])) {
            throw new \Exception("data de vencimento invalida");
        }



    }

    private function validateNum($num)
    {

        $num = preg_replace('/\D/', '', (string) $num);
        error_log("Número recebido: " . var_export($num, true));

        if (strlen($num) < 13 || strlen($num) > 19) {
            return false;
        }

        $sum = 0;
        $alt = false;

        //algoritmo de luhn, achei o código e mudei as informações. Conceito interessante e novo.
        for ($i = strlen($num) - 1; $i >= 0; $i--) {
            $dgt = (int) $num[$i];

            if ($alt) {
                $dgt *= 2;
                if ($dgt > 9) {
                    $dgt -= 9;
                }
            }

            $sum += $dgt;
            $alt = !$alt;
        }

        return ($sum % 10) === 0;
    }

    private function logTransacao(array $dados, array $retorno)
    {
        $logdata = [
            'timestamp' => date('Y-m-d H:i:s'),
            'pedido_id' => $dados['external_order_id'] ?? 'N/A',
            'valor' => $dados['amount'] ?? 'N/A',
            'cartao_final' => isset($dados['card_number']) ? '**** **** **** ' . substr($dados['card_number'], -4) : 'N/A',
            'status_transacao' => $retorno['Transaction_code'] ?? 'N/A',
            'mensagem' => $retorno['Message'] ?? 'N/A',
            'erro' => $retorno['Error'] ?? 'N/A'
        ];

        log_message('info', 'Transação PagCompleto: ' . json_encode($logdata));
    }

    private function validateDataVen($dados)
    {
        $dados = preg_replace('/\D/', '', $dados);

        if (strlen($dados) !== 4 && strlen($dados) !== 6) {
            return false;
        }

        if (strlen($dados) == 4) {
            $mes = (int) substr($dados, 0, 2); // MM
            $ano = (int) ('20' . substr($dados, 2, 2)); // YY
        } else {
            $mes = (int) substr($dados, 0, 2); // MM
            $ano = (int) substr($dados, 2, 4); // YYYY
        }

        if ($mes < 1 || $mes > 12) {
            return false;
        }

        try {
            $hj = new \DateTime();
            $venc = new \DateTime($ano . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01');
            $venc->modify('last day of this month');

            return $venc >= $hj;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function processaTransacao(array $dados)
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

        try {
            $this->validateDadosTr($dados);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Validação falha: ' . $e->getMessage()
            ];
        }

        try {
            $retornoRaw = $this->transacao($dados);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'exceção: ' . $e->getMessage()
            ];
        }

        if (isset($retornoRaw['Error']) && $retornoRaw['Error'] == true) {
            return [
                'error' => true,
                'code' => $retornoRaw['Transaction_code'] ?? '99',
                'message' => $retornoRaw['Message'] ?? 'Erro gateway'
            ];
        }

        if (isset($retornoRaw['Transaction_code']) && $retornoRaw['Transaction_code'] === '00') {
            return [
                'Error' => false,
                'Transaction_code' => $retornoRaw['Transaction_code'],
                'authorization_code' => $retornoRaw['authorization_code'] ?? null,
                'payment_id' => $retornoRaw['payment_id'] ?? null,
                'status' => 'approved'
            ];
        }

        return [
            'error' => true,
            'code' => $retornoRaw['Transaction_code'] ?? 'unknown',
            'message' => $retornoRaw['Message'] ?? 'recusado'
        ];

    }

    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;
    }
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

}