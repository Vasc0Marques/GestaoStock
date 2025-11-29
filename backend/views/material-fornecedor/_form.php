<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\MaterialFornecedor $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="material-fornecedor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'material_id')->textInput() ?>

    <?= $form->field($model, 'fornecedor_id')->textInput() ?>

    <?= $form->field($model, 'preco_base')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prazo_entrega_dias')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
