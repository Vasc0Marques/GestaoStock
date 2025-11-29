<?php

use common\models\EncomendaLinha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Encomenda Linhas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encomenda-linha-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Encomenda Linha', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'encomenda_id',
            'material_id',
            'nome_material',
            'quantidade',
            //'preco_unitario',
            //'subtotal',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, EncomendaLinha $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
