<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EncomendaLinha $model */

$this->title = 'Create Encomenda Linha';
$this->params['breadcrumbs'][] = ['label' => 'Encomenda Linhas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encomenda-linha-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
