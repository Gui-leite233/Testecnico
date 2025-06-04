<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pedidos extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>false,
                'null'=>false
            ],
            'valor_total'=>[
                'type'=>'NUMERIC',
                'constraint'=>'12, 2',
                'null'=>true
            ],
            'valor_frete'=>[
                'type'=>'NUMERIC',
                'constraint'=>'12, 2',
                'null'=>true
            ],
            'data'=>[
                'type'=>'TIMESTAMP',
                'null'=>true
            ],
            'id_cliente'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=>false
            ],
            'id_loja'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=>false
            ],
            'id_situacao'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=>false
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('pedidos', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('pedidos', true, true);  
    }
}
