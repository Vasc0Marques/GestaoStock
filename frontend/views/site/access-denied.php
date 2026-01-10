<?php

use yii\helpers\Html;

$this->title = 'Acesso Negado';
?>
<div style="display:flex;align-items:center;justify-content:center;min-height:60vh;flex-direction:column;text-align:center;padding:40px;">
    <div style="font-size:6rem;color:#e74c3c;margin-bottom:24px;">
        <i class="fa fa-ban"></i>
    </div>
    <h1 style="font-size:2.5rem;margin-bottom:16px;color:#2c3e50;">Acesso Negado</h1>
    <p style="font-size:1.3rem;color:#7f8c8d;margin-bottom:32px;max-width:600px;">
        Não tem permissões para aceder a esta página. Por favor, contacte o administrador se acha que isto é um erro.
    </p>
    <div>
        <?= Html::a('<i class="fa fa-sign-in"></i> Ir para Login', ['/site/login'], ['class' => 'btn btn-primary btn-lg', 'style' => 'margin-right:12px;']) ?>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('<i class="fa fa-home"></i> Voltar ao Início', Yii::$app->homeUrl, ['class' => 'btn btn-default btn-lg']) ?>
        <?php endif; ?>
    </div>
</div>
