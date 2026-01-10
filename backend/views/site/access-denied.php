<?php
use yii\helpers\Html;

$this->title = 'Acesso Negado';
?>
<div class="access-denied-container">
    <div class="access-denied-icon">
        <i class="fa fa-ban"></i>
    </div>
    <h1 class="access-denied-title">Acesso Negado</h1>
    <p class="access-denied-message">
        Não tem permissões para aceder a esta página. Por favor, contacte o administrador se acha que isto é um erro.
    </p>
    <div>
        <?= Html::a('<i class="fa fa-sign-in"></i> Ir para Login', ['/site/login'], ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
</div>
