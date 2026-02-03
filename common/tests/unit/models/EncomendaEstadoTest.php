<?php
namespace common\tests\unit\models;

use common\models\Encomenda;
use Codeception\Test\Unit;

class EncomendaEstadoTest extends Unit
{
    public function testDisplayEstadoWithValidAndInvalidValues()
    {
        $encomenda = new Encomenda([
            'fornecedor_id' => 1,
            'user_id' => 1,
            'data_encomenda' => date('Y-m-d'),
        ]);

        // Test null
        $encomenda->estado = null;
        $this->expectException(\Throwable::class);
        $encomenda->displayEstado();
    }

    public function testDisplayEstadoWithEmptyString()
    {
        $encomenda = new Encomenda([
            'fornecedor_id' => 1,
            'user_id' => 1,
            'data_encomenda' => date('Y-m-d'),
            'estado' => ''
        ]);
        $this->expectException(\Throwable::class);
        $encomenda->displayEstado();
    }

    public function testDisplayEstadoWithWrongType()
    {
        $encomenda = new Encomenda([
            'fornecedor_id' => 1,
            'user_id' => 1,
            'data_encomenda' => date('Y-m-d'),
            'estado' => 123
        ]);
        $this->expectException(\Throwable::class);
        $encomenda->displayEstado();
    }

    public function testDisplayEstadoWithInvalidValue()
    {
        $encomenda = new Encomenda([
            'fornecedor_id' => 1,
            'user_id' => 1,
            'data_encomenda' => date('Y-m-d'),
            'estado' => 'Inexistente'
        ]);
        $this->expectException(\Throwable::class);
        $encomenda->displayEstado();
    }

    public function testDisplayEstadoWithValidValues()
    {
        $encomenda = new Encomenda([
            'fornecedor_id' => 1,
            'user_id' => 1,
            'data_encomenda' => date('Y-m-d'),
        ]);
        foreach (array_keys(Encomenda::optsEstado()) as $estado) {
            $encomenda->estado = $estado;
            $this->assertEquals($estado, $encomenda->displayEstado());
        }
    }
}
