<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MaterialFornecedor $model */

$this->title = 'Update Material Fornecedor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Material Fornecedors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="material-fornecedor-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
