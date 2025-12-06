<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MaterialFornecedor $model */

$this->title = 'Create Material Fornecedor';
$this->params['breadcrumbs'][] = ['label' => 'Material Fornecedors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-fornecedor-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
