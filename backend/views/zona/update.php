<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Zona $model */

$this->title = 'Editar Zona: ' . $model->nome_zona;
$this->params['breadcrumbs'][] = ['label' => 'Zonas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome_zona, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="zona-update">

    <div class="panel panel-default" style="margin-top: 20px;">
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
