<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LojasGateway extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>false,
                'null'=>false,
            ],
            'id_loja'=>[
                'type' => 'INT',
                'unsigned' => true, 
                'null' => false,
            ],
            'id_gateway'=>[
                'type' => 'INT',
                'unsigned' => true, 
                'null' => false,
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('lojas_gateway', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('lojas_gateway', true, true);  
    }
}
