<?php
namespace backend\tests\functional;

use backend\tests\FunctionalTester;

class MaterialCreateCest
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

    public function createMaterial(FunctionalTester $I)
    {
        $I->amOnRoute('/material/create');
        $I->see('Adicionar Material');
        $I->fillField('Nome Material', 'Material Funcional');
        $I->fillField('Codigo', 'MATFUNC01');
        $I->selectOption('Categoria', '1');
        $I->selectOption('Zona', '1');
        $I->fillField('Unidade Medida', 'un');
        $I->fillField('Stock Minimo', '5');
        $I->click('Guardar');

        // Confirma na página de detalhe
        $I->see('Material Funcional');
        $I->see('MATFUNC01');
        $I->see('un');
        $I->see('5');
    }
}
