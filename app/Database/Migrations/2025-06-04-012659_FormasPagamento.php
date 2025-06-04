<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FormasPagamento extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => false,
                'null' => false,
            ],
            'descricao' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('formas_pagamento', true);
    }

    public function down()
    {
        $this->forge->dropTable('formas_pagamento', true, true);

    }
}
