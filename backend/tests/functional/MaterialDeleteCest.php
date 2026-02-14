<?php
namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\Material;

class MaterialDeleteCest
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

    public function deleteMaterial(FunctionalTester $I)
    {
        // Cria material para apagar
        $material = new Material([
            'nome_material' => 'Material Apagar',
            'codigo' => 'MATDEL01',
            'categoria_id' => 1,
            'zona_id' => 1,
            'unidade_medida' => 'un',
            'stock_minimo' => 1,
        ]);
        $material->save(false);

        // Vai à lista
        $I->amOnRoute('/material/index');
        $I->see('Material Apagar');
        $I->see('MATDEL01');

        // Clica no botão de apagar (assume que existe um botão/link com atributo data-method="post")
        $I->click(['css' => 'a[data-method="post"][href*="delete?id=' . $material->id . '"]']);
        $I->dontSee('Material Apagar');
        $I->dontSee('MATDEL01');
    }
}
