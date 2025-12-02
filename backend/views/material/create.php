<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Material $model */

$this->title = 'Adicionar Material';
$this->params['breadcrumbs'][] = ['label' => 'Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-create">

    <?= $this->render('_form', [
        'model' => $model,
        'categorias' => $categorias,
        'zonas' => $zonas,
    ]) ?>

</div>
