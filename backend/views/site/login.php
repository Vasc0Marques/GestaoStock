<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Login';
?>

<div class="login-container">
    <div class="login-left">
        <div class="login-left-content">
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <h1>Gestão Stock</h1>
            <p>Sistema de Gestão de Materiais e Inventário</p>
        </div>
    </div>
    
    <div class="login-right">
        <div class="login-box">
            <h2>Bem-vindo</h2>
            <p>Inicie sessão para continuar</p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'login-form']
            ]); ?>

                <?= $form->field($model, 'username')->textInput([
                    'autofocus' => true,
                    'placeholder' => 'Nome de utilizador',
                    'class' => 'form-control'
                ])->label(false) ?>

                <?= $form->field($model, 'password')->passwordInput([
                    'placeholder' => 'Palavra-passe',
                    'class' => 'form-control'
                ])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-sign-in"></i> Entrar', [
                        'class' => 'btn btn-primary btn-login',
                        'name' => 'login-button'
                    ]) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
