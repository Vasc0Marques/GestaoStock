<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Consultar Encomendas';
?>
<div class="container-fluid" style="padding: 20px;">
    <div style=" display: flex; flex-direction: column;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'panelFooterTemplate' => '{footer}',
            'containerOptions' => ['style' => 'height: 100% !important;'],
            'rowOptions' => function($model) {
                return [
                    'ondblclick' => "window.location='" . \yii\helpers\Url::to(['encomendas-terminal/view', 'id' => $model->id]) . "'",
                    'style' => 'cursor:pointer;',
                ];
            },
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'label' => 'ID',
                    'contentOptions' => ['class' => 'kv-align-center', 'style' => 'width:60px;'],
                    'headerOptions' => ['class' => 'kv-align-center', 'style' => 'width:60px;'],
                ],
                [
                    'attribute' => 'fornecedor_id',
                    'label' => 'Fornecedor',
                    'value' => function($model) {
                        return $model->fornecedor ? $model->fornecedor->nome_fornecedor : '';
                    },
                    'contentOptions' => ['style' => 'width:180px;'],
                    'headerOptions' => ['style' => 'width:180px;'],
                ],
                [
                    'attribute' => 'data_encomenda',
                    'label' => 'Data',
                    'contentOptions' => ['class' => 'kv-align-center', 'style' => 'width:120px;'],
                    'headerOptions' => ['class' => 'kv-align-center', 'style' => 'width:120px;'],
                ],
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
            ],
            'toolbar' => [],
            'panel' => [
                'type' => \kartik\grid\GridView::TYPE_DEFAULT,
                'heading' => $this->title,
            ],
            'export' => [
                'fontAwesome' => true
            ],
            'toggleDataOptions' => [
                'minCount' => 10
            ],
            'responsive' => true,
            'hover' => true,
            'summary' => 'Mostrando {begin}-{end} de {totalCount} encomendas.',
        ]); ?>
    </div>
</div>