<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Zona $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="zona-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'nome_zona')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nome da zona'
            ])->label('Nome da Zona')->hint('Nome único para identificar a zona de armazenamento') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descricao')->textarea([
                'rows' => 6,
                'placeholder' => 'Descrição detalhada da zona (opcional)'
            ])->label('Descrição')->hint('Adicione detalhes sobre a localização ou características da zona') ?>
        </div>
    </div>

    <div class="form-group form-actions">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Criar Zona' : 'Guardar Alterações',
            ['class' => 'btn btn-primary btn-lg']
        ) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
