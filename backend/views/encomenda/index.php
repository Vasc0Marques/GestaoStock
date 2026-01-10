<?php

use common\models\Encomenda;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Encomendas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encomenda-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panelFooterTemplate' => '{footer}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'fornecedor_id',
                'label' => 'Fornecedor',
                'value' => function($model) {
                    return $model->fornecedor ? $model->fornecedor->nome_fornecedor : '';
                }
            ],
            'data_encomenda',
            [
                'attribute' => 'estado',
                'label' => 'Estado',
                'format' => 'raw',
                'contentOptions' => ['class' => 'kv-align-center', 'style' => 'width: 110px;'],
                'headerOptions' => ['class' => 'kv-align-center', 'style' => 'width: 110px;'],
                'value' => function($model) {
                    $estado = strtolower($model->estado);
                    $map = [
                        'pendente' => 'estado-pendente',
                        'recebida' => 'estado-recebida',
                        'cancelada' => 'estado-cancelada',
                    ];
                    $class = isset($map[$estado]) ? $map[$estado] : 'estado-badge';
                    return '<span class="estado-badge ' . $class . '">' . \yii\helpers\Html::encode($model->estado) . '</span>';
                }
            ],
            [
                'attribute' => 'total',
                'label' => 'Total',
                'contentOptions' => ['class' => 'kv-align-center', 'style' => 'width: 110px;'],
                'headerOptions' => ['class' => 'kv-align-center', 'style' => 'width: 110px;'],
                'value' => function($model) {
                    return $model->total . ' €';
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, Encomenda $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
        'toolbar' => [],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $this->title,
            'footer' => Html::a('Create Encomenda', ['create'], ['class' => 'btn btn-success']),
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'toggleDataOptions' => [
            'minCount' => 10
        ],
        //double click to view
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['ondblclick' => 'window.location="' . Url::to(['encomenda/view', 'id' => $model->id]) . '";'];
        },
    ]); ?>

</div>
