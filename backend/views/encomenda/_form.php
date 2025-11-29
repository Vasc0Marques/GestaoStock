<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="encomenda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fornecedor_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'data_encomenda')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'Pendente' => 'Pendente', 'Recebida' => 'Recebida', 'Cancelada' => 'Cancelada', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacoes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'data_rececao')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
