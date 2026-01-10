<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Stock;
use yii\data\ActiveDataProvider;

class StockTerminalController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['consultar', 'saida'],
                'rules' => [
                    [
                        'actions' => ['consultar'],
                        'allow' => true,
                        'roles' => ['administrador', 'gestor', 'operador'],
                    ],
                    [
                        'actions' => ['saida'],
                        'allow' => true,
                        'roles' => ['administrador', 'gestor', 'operador'],
                    ],
                ],
            ],
        ];
    }
    public function actionConsultar()
    {
        // Corrige o nome da tabela para 'materiais' (não 'material')
        $query = Stock::find()->joinWith(['material']);
        $codigo = \Yii::$app->request->get('codigo');
        if ($codigo) {
            // Usa o nome correto da tabela: 'materiais'
            $query->andWhere(['materiais.codigo' => $codigo]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $this->render('consultar-stock', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSaida()
    {
        $request = \Yii::$app->request;
        $codigo = $request->get('codigo') ?: $request->post('codigo');
        $quantidadeSaida = $request->post('quantidade_saida');
        $mensagem = null;

        $material = null;
        $stockAtual = null;
        $movDataProvider = null;

        if ($codigo) {
            $material = \common\models\Material::find()->where(['codigo' => $codigo])->one();
            if ($material) {
                $stock = \common\models\Stock::find()->where(['material_id' => $material->id])->one();
                $stockAtual = $stock ? $stock->quantidade_atual : null;
                $movDataProvider = new \yii\data\ActiveDataProvider([
                    'query' => \common\models\Movimento::find()->where(['material_id' => $material->id])->orderBy(['data_movimentacao' => SORT_DESC]),
                    'pagination' => false,
                ]);
            }
        }

        if ($request->isPost && $codigo && $quantidadeSaida) {
            if ($material) {
                $stock = \common\models\Stock::find()->where(['material_id' => $material->id])->one();
                if ($stock && $stock->quantidade_atual >= $quantidadeSaida && $quantidadeSaida > 0) {
                    $stock->quantidade_atual -= $quantidadeSaida;
                    $stock->ultima_atualizacao = date('Y-m-d H:i:s');
                    $stock->save(false);

                    $mov = new \common\models\Movimento();
                    $mov->material_id = $material->id;
                    $mov->user_id = \Yii::$app->user->id;
                    $mov->tipo = \common\models\Movimento::TIPO_SAIDA;
                    $mov->quantidade = $quantidadeSaida;
                    $mov->data_movimentacao = date('Y-m-d H:i:s');
                    $mov->origem = 'Terminal';
                    $mov->save(false);

                    $mensagem = ['success', 'Saída registada com sucesso!'];
                    // Atualiza stockAtual e movDataProvider após saída
                    $stockAtual = $stock->quantidade_atual;
                    $movDataProvider = new \yii\data\ActiveDataProvider([
                        'query' => \common\models\Movimento::find()->where(['material_id' => $material->id])->orderBy(['data_movimentacao' => SORT_DESC]),
                        'pagination' => false,
                    ]);
                } else {
                    $mensagem = ['danger', 'Quantidade inválida ou insuficiente em stock.'];
                }
            } else {
                $mensagem = ['danger', 'Material não encontrado.'];
            }
        }

        return $this->render('saida-stock', [
            'mensagem' => $mensagem,
            'material' => $material,
            'stockAtual' => $stockAtual,
            'movDataProvider' => $movDataProvider,
        ]);
    }
}