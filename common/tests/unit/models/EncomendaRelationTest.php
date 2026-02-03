<?php
namespace common\tests\unit\models;

use common\models\Encomenda;
use common\models\EncomendaLinha;
use Yii;
use Codeception\Test\Unit;

class EncomendaRelationTest extends Unit
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

    public function testEncomendaHasManyEncomendaLinhas()
    {
        // Criar fornecedor
        $fornecedor = new \common\models\Fornecedor([
            'nome_fornecedor' => 'Fornecedor Teste',
        ]);
        $fornecedor->save(false);

        // Criar utilizador
        $user = new \common\models\User([
            'username' => 'user_teste',
            'auth_key' => 'testkey',
            'password_hash' => \Yii::$app->security->generatePasswordHash('123456'),
            'email' => 'user_teste@example.com',
        ]);
        $user->save(false);

        // Criar material
        $material = new \common\models\Material([
            'nome_material' => 'Material Teste',
            'codigo' => 'MATREL01',
            'categoria_id' => 1,
            'zona_id' => 1,
        ]);
        $material->save(false);

        $encomenda = new Encomenda([
            'fornecedor_id' => $fornecedor->id,
            'user_id' => $user->id,
            'data_encomenda' => date('Y-m-d'),
        ]);
        $this->assertTrue($encomenda->save(), 'Encomenda deve ser gravada');

        $linha1 = new EncomendaLinha([
            'encomenda_id' => $encomenda->id,
            'material_id' => $material->id,
            'nome_material' => 'Material 1',
            'quantidade' => 2,
            'preco_unitario' => 10.0,
        ]);
        $this->assertTrue($linha1->save(), 'Linha 1 deve ser gravada');

        $linha2 = new EncomendaLinha([
            'encomenda_id' => $encomenda->id,
            'material_id' => $material->id,
            'nome_material' => 'Material 2',
            'quantidade' => 1,
            'preco_unitario' => 20.0,
        ]);
        $this->assertTrue($linha2->save(), 'Linha 2 deve ser gravada');

        $linhas = $encomenda->getEncomendaLinhas()->all();
        $this->assertCount(2, $linhas, 'Encomenda deve ter 2 linhas');
    }

    public function testEncomendaLinhaInvalidForeignKeyFails()
    {
        $linha = new EncomendaLinha([
            'encomenda_id' => 999999, // ID inexistente
            'material_id' => 1,
            'nome_material' => 'Material X',
            'quantidade' => 1,
            'preco_unitario' => 5.0,
        ]);
        $this->assertFalse($linha->validate(), 'FK inválida deve falhar validação');
        $this->assertArrayHasKey('encomenda_id', $linha->getErrors());
    }
}
