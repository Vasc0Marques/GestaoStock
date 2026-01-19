<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Zona $model */

$this->title = 'Criar Zona';
$this->params['breadcrumbs'][] = ['label' => 'Zonas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zona-create">


    <div class="panel panel-default" style="margin-top: 20px;">
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
