<?php

/** @var yii\web\View $this */

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

$this->title = 'Gestão Stock';

// DataProvider para todos os movimentos (ajuste conforme necessário)
$dataProvider = new ActiveDataProvider([
    'query' => \common\models\Movimento::find()->orderBy(['data_movimentacao' => SORT_DESC]),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>
<div style="padding: 20px; height: 100%;">
    <h2>Histórico de Movimentações</h2>
    <div class="content-body" style="margin-top:24px;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => 'Mostrando {begin}-{end} de {totalCount} movimentos.',
            'bordered' => true,
            'striped' => true,
            'hover' => true,
            'condensed' => false,
            'columns' => [
                ['class' => 'kartik\\grid\\SerialColumn'],
                [
                    'attribute' => 'material_id',
                    'label' => 'Material',
                    'value' => function($mov) {
                        return $mov->material ? $mov->material->nome_material : '';
                    }
                ],
                [
                    'attribute' => 'tipo',
                    'label' => 'Tipo',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'kv-align-center'],
                    'value' => function($mov) {
                        $tipo = strtolower($mov->tipo);
                        $class = $tipo === 'entrada' ? 'tipo-entrada' : 'tipo-saida';
                        return '<span class="tipo-badge ' . $class . '">' . Html::encode(ucfirst($mov->tipo)) . '</span>';
                    }
                ],
                [
                    'attribute' => 'quantidade',
                    'label' => 'Quantidade',
                ],
                [
                    'attribute' => 'data_movimentacao',
                    'label' => 'Data',
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Utilizador',
                    'value' => function($mov) {
                        return $mov->user ? $mov->user->getNomeCompleto() : '';
                    }
                ],
            ],
            'panel' => [
                'type' => kartik\grid\GridView::TYPE_DEFAULT,
                'heading' => false,
                'footer' => false,
            ],
            'toolbar' => [],
            'export' => [
                'fontAwesome' => true
            ],
            'toggleDataOptions' => [
                'minCount' => 10
            ],
            'responsive' => true,
        ]); ?>
    </div>
</div>