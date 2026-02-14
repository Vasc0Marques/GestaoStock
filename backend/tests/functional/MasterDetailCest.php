<?php
namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\Encomenda;
use common\models\EncomendaLinha;

class MasterDetailCest
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

    public function masterDetailFlow(FunctionalTester $I)
    {
        // Cria encomenda e linhas
        $encomenda = new Encomenda([
            'fornecedor_id' => 1,
            'user_id' => 1,
            'data_encomenda' => date('Y-m-d'),
            'estado' => Encomenda::ESTADO_PENDENTE,
        ]);
        $encomenda->save(false);

        $linha1 = new EncomendaLinha([
            'encomenda_id' => $encomenda->id,
            'material_id' => 1,
            'nome_material' => 'Material 1',
            'quantidade' => 2,
            'preco_unitario' => 10.0,
        ]);
        $linha1->save(false);

        $linha2 = new EncomendaLinha([
            'encomenda_id' => $encomenda->id,
            'material_id' => 1,
            'nome_material' => 'Material 2',
            'quantidade' => 1,
            'preco_unitario' => 20.0,
        ]);
        $linha2->save(false);

        // 2) Abre lista de encomendas
        $I->amOnRoute('/encomenda/index');
        $I->see($encomenda->id);

        // 3) Clica no item (assume link para view)
        $I->click(['link' => $encomenda->id]);

        // 4) Confirma página de detalhe
        $I->see($encomenda->id);
        $I->see($encomenda->data_encomenda);
        $I->see($encomenda->estado);
        // Sublista relacionada
        $I->see('Material 1');
        $I->see('Material 2');
        $I->see('2');
        $I->see('1');
    }
}
