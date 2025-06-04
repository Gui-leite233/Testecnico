<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Gateways extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => false,
                'null' => false,
            ],
            'descricao'=>[
                'type'=>'TEXT',
                'null'=>true
            ],
            'endpoint'=>[
                'type'=>'TEXT',
                'null'=>true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('gateways', true);
    }

    public function down()
    {
        $this->forge->dropTable('gateways', true, true);  
    }
}
