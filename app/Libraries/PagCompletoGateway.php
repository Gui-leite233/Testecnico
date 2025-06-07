<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use finfo;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\New_;
use App\Controllers\ProcessoPagamentoController;

class PagCompletoGateway
{
    private $baseURL;
    private $endpoint;
    private $accessToken;
    private $timeout;


    public function __construct()
    {
        $this->baseURL='https://apiinterna.ecompleto.com.br/exams/processTransaction';
        $this->endpoint='/exams/processTransaction';
        $this->accessToken=env('PAGCOMPLETO_ACCESS_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjI2ODQsInN0b3JlSWQiOjE5NzksImlhdCI6MTc0ODU0NTIwNiwiZXhwIjoxNzQ5NDA5MjA2fQ.pngOSO40bI67Q1bCwWc_SFdIuBRhDQKww2DyxfhTKqo');
        $this->timeout = 90;
    }


    public function transacao(array $dadosPagamento)
    {
        $url = $this->baseURL . '?accessToken=' . $this->accessToken;
        

        $jsonPayload = json_encode($dadosPagamento, JSON_UNESCAPED_UNICODE);
        

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonPayload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $response = curl_exec($curl);
        $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        $retorno = json_decode($response, true);

        return $retorno;

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