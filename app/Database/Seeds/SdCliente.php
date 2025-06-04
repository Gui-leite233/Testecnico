<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SdCliente extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 8796,
                'nome' => 'Emanuelly Alice Alessandra de Paula',
                'cpf_cnpj' => '96446953722',
                'email' => 'emanuellyalice@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1988-01-18',
                'id_loja' => 90
            ],
            [
                'id' => 5789,
                'nome' => 'Renato Ryan Lopes',
                'cpf_cnpj' => '78891957615',
                'email' => 'renato_ryan@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1947-02-08',
                'id_loja' => 92
            ],
            [
                'id' => 6748,
                'nome' => 'Kauê Bryan Souza',
                'cpf_cnpj' => '55782338806',
                'email' => 'kauesouza@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1945-06-27',
                'id_loja' => 90
            ],
            [
                'id' => 6872,
                'nome' => 'Samuel Emanuel Castro',
                'cpf_cnpj' => '85673855800',
                'email' => 'samuel.castro@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1988-11-05',
                'id_loja' => 115
            ],
            [
                'id' => 6716,
                'nome' => 'Raquel Nicole Moura',
                'cpf_cnpj' => '36118844720',
                'email' => 'raquelnicole_moura@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1990-02-20',
                'id_loja' => 98
            ],
            [
                'id' => 4802,
                'nome' => 'Fernando Julio Ramos',
                'cpf_cnpj' => '20499776461',
                'email' => 'fernando_julio99@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1999-09-11',
                'id_loja' => 97
            ],
            [
                'id' => 9484,
                'nome' => 'Kevin Yuri Pedro Lopes',
                'cpf_cnpj' => '95829123088',
                'email' => 'kevinpedro@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1996-06-03',
                'id_loja' => 94
            ],
            [
                'id' => 1830,
                'nome' => 'Thales André Pereira',
                'cpf_cnpj' => '13440817709',
                'email' => 'samuel.castro@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1995-04-07',
                'id_loja' => 90
            ],
            [
                'id' => 2280,
                'nome' => 'Heloisa Valentina Fabiana Moura',
                'cpf_cnpj' => '99386767660',
                'email' => 'heloisavalentina@ecompleto.com.br',
                'tipo_pessoa' => 'F',
                'data_nasc' => '1984-12-12',
                'id_loja' => 92
            ]
        ];

        $this->db->table('clientes')->insertBatch($data);
    }
}
