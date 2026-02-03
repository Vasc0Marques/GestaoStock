<?php
namespace common\tests\unit\models;

use common\models\Material;
use Codeception\Test\Unit;

class MaterialTest extends Unit
{
    public function testValidationFailsWithEmptyRequiredFields()
    {
        $material = new Material();
        $this->assertFalse($material->validate(), 'Validation should fail when required fields are empty');
        $errors = $material->getErrors();
        $this->assertArrayHasKey('nome_material', $errors);
        $this->assertArrayHasKey('codigo', $errors);
        $this->assertArrayHasKey('categoria_id', $errors);
        $this->assertArrayHasKey('zona_id', $errors);
    }

    public function testValidationPassesWithMinimumFields()
    {
        $material = new Material([
            'nome_material' => 'Material Teste',
            'codigo' => 'MAT001',
            'categoria_id' => 1,
            'zona_id' => 1,
        ]);
        $this->assertTrue($material->validate(), 'Validation should pass with minimum required fields');
    }
}
