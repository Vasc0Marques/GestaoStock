<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Material $model */

$this->title = $model->nome_material;
$this->params['breadcrumbs'][] = ['label' => 'Materials', 'url' => ['index']];
\yii\web\YiiAsset::register($this);
?>
<div class="material-view">


    <div style="margin-bottom:20px;">
        <!-- Placeholder da foto do material -->
        <img src="https://via.placeholder.com/150?text=Foto+Material" alt="Foto do Material" style="border-radius:8px;box-shadow:0 2px 8px #ccc;">
    </div>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Nome:</strong> <?= Html::encode($model->nome_material) ?></li>
        <li class="list-group-item"><strong>Código:</strong> <?= Html::encode($model->codigo) ?></li>
        <li class="list-group-item"><strong>Categoria:</strong> <?= $model->categoria ? Html::encode($model->categoria->nome_categoria) : '-' ?></li>
        <li class="list-group-item"><strong>Zona:</strong> <?= $model->zona ? Html::encode($model->zona->nome_zona) : '-' ?></li>
        <li class="list-group-item"><strong>Unidade de Medida:</strong> <?= Html::encode($model->unidade_medida) ?></li>
        <li class="list-group-item"><strong>Stock Mínimo:</strong> <?= Html::encode($model->stock_minimo) ?></li>
        <li class="list-group-item"><strong>Criado em:</strong> <?= Html::encode($model->criado_em) ?></li>
    </ul>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
