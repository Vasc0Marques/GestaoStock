<?php

use common\models\Fornecedor;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fornecedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fornecedor-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panelFooterTemplate' => '{footer}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            //'id',
            'nome_fornecedor',
            'email',
            'telefone',
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, Fornecedor $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
        'toolbar' => [],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $this->title,
            'footer' => Html::a('Create Fornecedor', ['create'], ['class' => 'btn btn-success']),
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'toggleDataOptions' => [
            'minCount' => 10
        ],
        //double click to view
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['ondblclick' => 'window.location="' . Url::to(['fornecedor/view', 'id' => $model->id]) . '";'];
        },
    ]); ?>

</div>
