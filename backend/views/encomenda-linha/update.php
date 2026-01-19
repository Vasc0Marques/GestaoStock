<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EncomendaLinha $model */
/** @var array $materiais */

$this->title = 'Editar Linha da Encomenda #' . $model->encomenda_id;
$this->params['breadcrumbs'][] = ['label' => 'Encomendas', 'url' => ['/encomenda/index']];
$this->params['breadcrumbs'][] = ['label' => 'Encomenda #' . $model->encomenda_id, 'url' => ['/encomenda/view', 'id' => $model->encomenda_id]];
$this->params['breadcrumbs'][] = 'Editar Linha';
?>
<div class="encomenda-linha-update">

    <?= $this->render('_form', [
        'model' => $model,
        'materiais' => $materiais,
    ]) ?>

</div>
