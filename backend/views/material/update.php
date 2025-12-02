<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Material $model */

$this->title = 'Editar Material: ' . $model->nome_material;
$this->params['breadcrumbs'][] = ['label' => 'Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="material-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categorias' => $categorias,
        'zonas' => $zonas,
    ]) ?>

</div>
