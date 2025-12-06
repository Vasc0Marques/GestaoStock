<?php

use common\models\EncomendaLinha;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Encomenda Linhas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encomenda-linha-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'containerOptions' => ['style' => 'height: 440px !important;'],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            'encomenda_id',
            'material_id',
            'nome_material',
            'quantidade',
            //'preco_unitario',
            //'subtotal',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, EncomendaLinha $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'toolbar' => [],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $this->title,
            'footer' => Html::a('Create Encomenda Linha', ['create'], ['class' => 'btn btn-success']),
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'toggleDataOptions' => [
            'minCount' => 10
        ],
    ]); ?>

</div>
