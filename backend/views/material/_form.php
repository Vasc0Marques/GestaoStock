<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
3

/** @var yii\web\View $this */
/** @var common\models\Material $model */
/** @var yii\widgets\ActiveForm $form */


?>

<div class="material-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome_material')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
        $categorias,
        ['prompt' => ['text' => 'Selecione a categoria', 'options' => ['disabled' => true, 'selected' => true]]]
    ) ?>

    <?= $form->field($model, 'zona_id')->dropDownList(
        $zonas,
        ['prompt' => ['text' => 'Selecione a zona', 'options' => ['disabled' => true, 'selected' => true]]]
    ) ?>

    <?= $form->field($model, 'unidade_medida')->dropDownList([
        'un' => 'Unidades (un)',
        'kg' => 'Quilogramas (Kg)',
        'm' => 'Metros (m)',
        'm2' => 'Metros Quadrados (m²)',
    ], ['prompt' => ['text' => 'Selecione a unidade', 'options' => ['disabled' => true, 'selected' => true]]]) ?>

    <?= $form->field($model, 'stock_minimo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
