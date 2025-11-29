<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MaterialFornecedor $model */

$this->title = 'Create Material Fornecedor';
$this->params['breadcrumbs'][] = ['label' => 'Material Fornecedors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-fornecedor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
