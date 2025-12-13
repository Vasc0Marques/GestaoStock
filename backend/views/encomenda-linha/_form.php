<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\EncomendaLinha $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $materiais */

?>

<div class="encomenda-linha-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'encomenda_id') ?>

    <?= $form->field($model, 'material_id')->dropDownList(
        $materiais,
        [
            'prompt' => 'Selecione o material...',
            'class' => 'form-control selectpicker',
            'data-live-search' => 'true'
        ]
    ) ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
