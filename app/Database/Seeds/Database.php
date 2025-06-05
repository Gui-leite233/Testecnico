<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Database extends Seeder
{
    public function run()
    {
        //rode esta seeder para rodar todas as outras
        $this->call(SdForPa::class);
        $this->call(SdCliente::class);
        $this->call(SdGateWays::class);
        $this->call(SdLojGway::class);
        $this->call(SdPedidos::class);
        $this->call(SdPedPag::class);
        $this->call(SdPedSitu::class);
    }
}