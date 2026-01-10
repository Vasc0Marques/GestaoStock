<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true, 'value' => '']) ?>

    <?= $form->field($model, 'pin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cargo')->dropDownList([
        'administrador' => 'Administrador',
        'gestor' => 'Gestor',
        'operador' => 'Operador',
    ], ['prompt' => 'Selecione o cargo...']) ?>

    <?= $form->field($model, 'status')->dropDownList([
        10 => 'Ativo',
        0 => 'Inativo',
    ], ['prompt' => 'Selecione o estado...']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
