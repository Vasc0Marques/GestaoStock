<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */

$this->title = 'Create Encomenda';
$this->params['breadcrumbs'][] = ['label' => 'Encomendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encomenda-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
