<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Consultar Encomendas';
?>
<style>
.estado-badge {
    display: inline-block;
    padding: 0.35em 0.9em;
    font-size: 1em;
    font-weight: 600;
    border-radius: 0.7em;
    border: 2px solid transparent;
    background: #f8f9fa;
    min-width: 90px;
    text-align: center;
    margin: 0 auto;
}
.estado-pendente {
    color: #b36b00;
    border-color: #fd7e14;
    background: #fff7e6;
}
.estado-recebida {
    color: #198754;
    border-color: #198754;
    background: #e9fbe8;
}
.estado-cancelada {
    color: #dc3545;
    border-color: #dc3545;
    background: #fbeaea;
}
.kv-align-center {
    text-align: center !important;
    vertical-align: middle !important;
}
</style>
<div class="container-fluid" style="padding: 20px;">
    <div style=" display: flex; flex-direction: column;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'panelFooterTemplate' => '{footer}',
            'containerOptions' => ['style' => 'height: 100% !important;'],
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