<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PedidosPagamentos extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => false,
                'null' => false,
            ],
            'id_pedido' => [
                'type' => 'INT',
                'unsigned' => true, 
                'null' => false,
            ],
            'id_formatopagto' => [
                'type' => 'INT',
                'unsigned' => true, 
                'null' => false,
            ],
            'qtd_parcelas' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'retorno_intermediador' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'data_processamento' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'num_cartao' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true 
            ],
            'nome_portador' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true 
            ],
            'codigo_verificacao' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'vencimento' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true
            ]
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pedidos_pagamentos', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('pedidos_pagamentos', true, true);  
    }
}