<?php

use common\models\Material;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Materials';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="material-index">
    <p>
        <?= Html::a('Create Material', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'nome_material',
            'codigo',
            [
                'attribute' => 'categoria_id',
                'label' => 'Categoria',
                'value' => function ($model) {
                    return $model->categoria ? $model->categoria->nome_categoria : '';
                }
            ],
            [
                'attribute' => 'zona_id',
                'label' => 'Zona',
                'value' => function ($model) {
                    return $model->zona ? $model->zona->nome_zona : '';
                }
            ],
            'unidade_medida',
            'stock_minimo',
            //'criado_em',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Material $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
        // Optional: enable export, toolbar, etc.
        'toolbar' => [
           
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $this->title,
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'toggleDataOptions' => [
            'minCount' => 10
        ],
    ]); ?>

</div>
