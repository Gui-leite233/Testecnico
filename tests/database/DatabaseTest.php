<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use PhpParser\Node\Expr\FuncCall;

class DatabaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $migrateUm = false;
    protected $refresh = true;


    public function testConexao(){
        $db = \Config\Database::connect();
        $this->assertTrue($db->connID!==false);
    }

    public function testMigration(){
        
        /*$this->assertTrue($this->db->tableExists('clientes'));
        $this->assertTrue($this->db->tableExists('pedidos'));
        $this->assertTrue($this->db->tableExists('pedidos_pagamentos'));
        $this->assertTrue($this->db->tableExists('lojas_gateway'));
        $this->assertTrue($this->db->tableExists('pedido_situacao'));
        $this->assertTrue($this->db->tableExists('formas_pagamento'));
        $this->assertTrue($this->db->tableExists('gateways'));*/


         $expectedTables = [
            'clientes',
            'formas_pagamento',
            'gateways',
            'lojas_gateway',
            'pedidos',
            'pedido_situacao',
            'pedidos_pagamentos',
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(
                $this->db->tableExists($table), 
                "$table não existe"
            );
        }
    }


    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExample(): void
    {
        //
    }
}
