<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Categoria $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categoria-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'nome_categoria')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nome da categoria'
            ])->label('Nome da Categoria')->hint('Nome único para identificar a categoria') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descricao')->textarea([
                'rows' => 6,
                'placeholder' => 'Descrição detalhada da categoria (opcional)'
            ])->label('Descrição')->hint('Adicione detalhes sobre o que esta categoria engloba') ?>
        </div>
    </div>

    <div class="form-group form-actions">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Criar Categoria' : 'Guardar Alterações',
            ['class' => 'btn btn-primary btn-lg']
        ) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
