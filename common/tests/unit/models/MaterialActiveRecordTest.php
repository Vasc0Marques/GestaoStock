<?php
namespace common\tests\unit\models;

use common\models\Material;
use Yii;
use Codeception\Test\Unit;

class MaterialActiveRecordTest extends Unit
{
    private $transaction;

    protected function _before()
    {
        $this->transaction = Yii::$app->db->beginTransaction();
    }

    protected function _after()
    {
        $this->transaction->rollBack();
    }

    public function testActiveRecordCrud()
    {
        // 1) Criar registo
        $material = new Material([
            'nome_material' => 'Material Teste AR',
            'codigo' => 'MATAR001',
            'categoria_id' => 1,
            'zona_id' => 1,
        ]);
        $this->assertTrue($material->save(), 'Deve gravar material válido');
        $id = $material->id;

        // 2) Confirmar que foi gravado
        $found = Material::findOne($id);
        $this->assertNotNull($found, 'Material deve existir na BD');
        $this->assertEquals('Material Teste AR', $found->nome_material);

        // 3) Atualizar campo
        $found->nome_material = 'Material Atualizado';
        $this->assertTrue($found->save(), 'Deve gravar atualização');
        $updated = Material::findOne($id);
        $this->assertEquals('Material Atualizado', $updated->nome_material);

        // 4) Apagar registo
        $updated->delete();

        // 5) Confirmar que já não existe
        $deleted = Material::findOne($id);
        $this->assertNull($deleted, 'Material deve ter sido apagado');
    }
}
