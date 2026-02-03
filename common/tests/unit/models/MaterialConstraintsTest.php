<?php
namespace common\tests\unit\models;

use common\models\Material;
use Codeception\Test\Unit;

class MaterialConstraintsTest extends Unit
{
    public function testInvalidValuesShouldFailValidation()
    {
        $material = new Material([
            'nome_material' => '', // obrigatório
            'codigo' => '', // obrigatório e unique
            'categoria_id' => 'abc', // deve ser inteiro
            'zona_id' => null, // obrigatório
            'stock_minimo' => -5, // deve ser inteiro >= 0
        ]);
        $this->assertFalse($material->validate(), 'Validation should fail for invalid values');
        $errors = $material->getErrors();
        $this->assertArrayHasKey('nome_material', $errors);
        $this->assertArrayHasKey('codigo', $errors);
        $this->assertArrayHasKey('categoria_id', $errors);
        $this->assertArrayHasKey('zona_id', $errors);
        $this->assertArrayHasKey('stock_minimo', $errors);
    }

    public function testValidValuesShouldPassValidation()
    {
        $material = new Material([
            'nome_material' => 'Material Teste',
            'codigo' => 'MAT002',
            'categoria_id' => 1,
            'zona_id' => 1,
            'stock_minimo' => 0,
        ]);
        $this->assertTrue($material->validate(), 'Validation should pass for valid values');
    }

    public function testUniqueCodigoConstraint()
    {
        $material1 = new Material([
            'nome_material' => 'Material 1',
            'codigo' => 'UNIQ001',
            'categoria_id' => 1,
            'zona_id' => 1,
        ]);
        $this->assertTrue($material1->save(), 'First material with unique codigo should save');

        $material2 = new Material([
            'nome_material' => 'Material 2',
            'codigo' => 'UNIQ001', // mesmo código
            'categoria_id' => 1,
            'zona_id' => 1,
        ]);
        $this->assertFalse($material2->save(), 'Second material with duplicate codigo should fail');
        $this->assertArrayHasKey('codigo', $material2->getErrors());
    }
}
