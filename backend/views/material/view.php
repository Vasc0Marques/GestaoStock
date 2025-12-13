<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use common\models\Stock;

/** @var yii\web\View $this */
/** @var common\models\Material $model */
/** @var yii\data\ActiveDataProvider $movimentacoesDataProvider */

$this->title = $model->nome_material;
$this->params['breadcrumbs'][] = ['label' => 'Materiais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Buscar o stock atual do material
$stock = Stock::find()->where(['material_id' => $model->id])->one();
$stockAtual = $stock ? $stock->quantidade_atual : 0;
?>
<div class="row" style="min-height: 90vh;">
    <div class="col-md-5">
        <div class="material-view">
            <div class="text-center" style="margin-bottom: 24px;">
                <img src="https://via.placeholder.com/120x120?text=Foto" alt="Foto do Material" class="img-thumbnail" style="width:220px;height:220px;">
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Categoria',
                        'value' => $model->categoria ? $model->categoria->nome_categoria : '',
                    ],
                    [
                        'label' => 'Zona',
                        'value' => $model->zona ? $model->zona->nome_zona : '',
                    ],
                    'nome_material',
                    'codigo',
                    'unidade_medida',
                    'stock_minimo',
                    [
                        'label' => 'Stock Atual',
                        'value' => $stockAtual,
                    ],
                    'criado_em',
                    // ...adicione outros atributos se necessário...
                ],
            ]) ?>
            <div class="text-center" style="margin-top: 24px;">
                <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Apagar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Tem a certeza que pretende apagar este item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-7" style="display: flex; flex-direction: column; height: 85vh;">
        <div class="panel panel-default" style="flex: 1 1 auto; display: flex; flex-direction: column; height: 100%;">
            <div class="panel-heading"><b>Movimentações</b></div>
            <div class="panel-body" style="flex: 1 1 auto; overflow-y: auto;">
                <?= GridView::widget([
                    'dataProvider' => $movimentacoesDataProvider,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],
                        'tipo',
                        'quantidade',
                        'data_movimentacao',
                        [
                            'attribute' => 'user_id',
                            'label' => 'Utilizador',
                            'value' => function($model) {
                                return $model->user ? $model->user->username : '';
                            }
                        ],
                        // ...adicione outras colunas relevantes...
                    ],
                    'panel' => false,
                    'toolbar' => false,
                    'summary' => '',
                ]); ?>
            </div>
        </div>
    </div>
</div>
