<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'username')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nome de utilizador único'
            ])->label('Username') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'placeholder' => 'exemplo@email.com'
            ])->label('Email') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nomeProprio')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nome'
            ])->label('Nome Próprio') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'apelido')->textInput([
                'maxlength' => true,
                'placeholder' => 'Apelido'
            ])->label('Apelido') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'password_hash')->passwordInput([
                'maxlength' => true,
                'value' => '',
                'placeholder' => $model->isNewRecord ? 'Defina uma password' : 'Deixe vazio para manter a atual'
            ])->label('Password')->hint($model->isNewRecord ? 'Password do utilizador' : 'Deixe vazio para não alterar a password') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'pin')->textInput([
                'maxlength' => 4,
                'placeholder' => 'PIN de 4 dígitos'
            ])->label('PIN')->hint('PIN numérico de 4 dígitos para login no terminal') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'cargo')->dropDownList([
                'administrador' => 'Administrador',
                'gestor' => 'Gestor',
                'operador' => 'Operador',
            ], [
                'prompt' => 'Selecione o cargo...',
                'options' => [
                    $model->cargo => ['selected' => true]
                ]
            ])->label('Cargo')->hint('Define as permissões do utilizador') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList([
                10 => 'Ativo',
                9 => 'Inativo',
                0 => 'Eliminado',
            ], [
                'prompt' => 'Selecione o estado...',
                'options' => [
                    $model->status => ['selected' => true]
                ]
            ])->label('Estado')->hint('Utilizadores inativos não podem fazer login') ?>
        </div>
    </div>

    <div class="form-group form-actions">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Criar Utilizador' : 'Guardar Alterações',
            ['class' => 'btn btn-primary btn-lg']
        ) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
