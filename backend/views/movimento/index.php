<?php

use common\models\Movimento;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Movimentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movimento-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panelFooterTemplate' => '{footer}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'material_id',
                'label' => 'Material',
                'value' => function($model) {
                    return $model->material ? $model->material->nome_material : '';
                }
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Utilizador',
                'value' => function($model) {
                    return $model->user ? $model->user->getNomeCompleto() : '';
                }
            ],
            [
                'attribute' => 'tipo',
                'label' => 'Tipo',
                'format' => 'raw',
                'contentOptions' => ['class' => 'kv-align-center', 'style' => 'width: 110px;'],
                'headerOptions' => ['class' => 'kv-align-center', 'style' => 'width: 110px;'],
                'value' => function($model) {
                    $tipo = strtolower($model->tipo);
                    $class = $tipo === 'entrada' ? 'tipo-entrada' : 'tipo-saida';
                    return '<span class="tipo-badge ' . $class . '">' . Html::encode(ucfirst($model->tipo)) . '</span>';
                }
            ],
            'quantidade',
            'data_movimentacao',
        ],
        'toolbar' => [],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $this->title,
            'footer' => false,
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'toggleDataOptions' => [
            'minCount' => 10
        ],
    ]); ?>

</div>
