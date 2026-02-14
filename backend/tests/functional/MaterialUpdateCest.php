<?php
namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\Material;

class MaterialUpdateCest
{
    public function _before(FunctionalTester $I)
    {
        // Login helper reutilizado
        $I->amOnRoute('/site/login');
        $I->fillField(['name' => 'LoginForm[username]'], 'vasco');
        $I->fillField(['name' => 'LoginForm[password]'], '1');
        $I->click('login-button');
        $I->see('Logout (vasco)');
    }

    public function updateMaterial(FunctionalTester $I)
    {
        // Cria material para editar
        $material = new Material([
            'nome_material' => 'Material Editar',
            'codigo' => 'MATUPD01',
            'categoria_id' => 1,
            'zona_id' => 1,
            'unidade_medida' => 'un',
            'stock_minimo' => 2,
        ]);
        $material->save(false);

        // Vai à página de update
        $I->amOnRoute('/material/update', ['id' => $material->id]);
        $I->see('Atualizar Material');
        $I->fillField('Nome Material', 'Material Editado');
        $I->click('Guardar');

        // Confirma persistência
        $I->see('Material Editado');
        $I->see('MATUPD01');
    }
}
