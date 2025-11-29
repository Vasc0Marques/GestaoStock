<?php

use common\models\MaterialFornecedor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Material Fornecedors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-fornecedor-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Material Fornecedor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'material_id',
            'fornecedor_id',
            'preco_base',
            'prazo_entrega_dias',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MaterialFornecedor $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
