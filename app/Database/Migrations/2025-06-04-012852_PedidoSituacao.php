<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PedidoSituacao extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>false,
                'null'=>false
            ],
            'descricao'=>[
                'type'=>'VARCHAR',
                'constraint'=>'50',
                'null'=>false
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pedido_situacao', true);
    }

    public function down()
    {
        $this->forge->dropTable('pedido_situacao', true, true);  
    }
}
