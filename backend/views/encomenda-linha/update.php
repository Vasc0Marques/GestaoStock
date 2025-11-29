<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EncomendaLinha $model */

$this->title = 'Update Encomenda Linha: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Encomenda Linhas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="encomenda-linha-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
