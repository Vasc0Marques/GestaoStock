<?php

namespace api\controllers;

use common\models\Encomenda;

class EncomendasController extends BaseApiController
{
    /**
     * GET /api/encomendas/{id}
     */
    public function actionView($id)
    {
        $encomenda = Encomenda::find()
            ->with(['fornecedor', 'encomendaLinhas.material'])
            ->where(['id' => $id])
            ->one();

        if (!$encomenda) {
            return $this->error('not_found', 'Encomenda não encontrada.', 404);
        }

        return $this->success([
            'id' => $encomenda->id,
            'fornecedor' => $encomenda->fornecedor ? $encomenda->fornecedor->nome_fornecedor : null,
            'linhas' => array_map(function($linha) {
                return [
                    'material' => $linha->material ? $linha->material->nome_material : $linha->nome_material,
                    'quantidade' => $linha->quantidade,
                ];
            }, $encomenda->encomendaLinhas),
        ]);
    }
}
