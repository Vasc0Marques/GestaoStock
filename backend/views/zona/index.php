<?php

use common\models\Zona;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Zonas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zona-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'panelFooterTemplate' => '{footer}',
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'nome_zona',
            'descricao:ntext',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Zona $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
        'toolbar' => [],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $this->title,
            'footer' => Html::a('Adicionar Zona', ['create'], ['class' => 'btn btn-success']),
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'toggleDataOptions' => [
            'minCount' => 10
        ],
    ]); ?>

</div>
