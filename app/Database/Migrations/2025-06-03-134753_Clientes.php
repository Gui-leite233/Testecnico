<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Clientes extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => false,
            ],
            'nome' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cpf_cnpj' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'email' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tipo_pessoa' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'data_nasc' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'id_loja' => [
                'type'     => 'INT',
                'null'     => true,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id', true); // Define 'id' como chave primária
        $this->forge->createTable('clientes', true); // Cria a tabela 'clientes' se não existir
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('clientes', true, true);
    }
}
