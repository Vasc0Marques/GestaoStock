<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Fornecedor;
use yii\helpers\ArrayHelper;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */
/** @var yii\widgets\ActiveForm $form */

// Lista de fornecedores para o dropdown
$fornecedores = ArrayHelper::map(Fornecedor::find()->orderBy('nome_fornecedor')->all(), 'id', 'nome_fornecedor');

// Preenche o user_id com o usuário logado, se novo registro
if ($model->isNewRecord && Yii::$app->user && !Yii::$app->user->isGuest) {
    $model->user_id = Yii::$app->user->id;
    if (empty($model->data_encomenda)) {
        $model->data_encomenda = date('Y-m-d\TH:i');
    }
}

// Ajusta o formato da data_encomenda para o input datetime-local ao editar
if (!$model->isNewRecord && !empty($model->data_encomenda)) {
    $dt = strtotime($model->data_encomenda);
    if ($dt) {
        $model->data_encomenda = date('Y-m-d\TH:i', $dt);
    }
}

// Sempre deixa data_rececao vazia no form
$model->data_rececao = '';

// Busca o nome/apelido do user logado
$userNome = '';
if ($model->user_id) {
    $user = User::findOne($model->user_id);
    if ($user) {
        $userNome = trim($user->username . (isset($user->apelido) ? ' ' . $user->apelido : ''));
    }
}
?>

<div class="encomenda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fornecedor_id')->dropDownList(
        $fornecedores,
        [
            'prompt' => 'Selecione o fornecedor...',
            'class' => 'form-control selectpicker',
            'data-live-search' => 'true'
        ]
    ) ?>

    <?= Html::activeHiddenInput($model, 'user_id') ?>

    <div class="form-group">
        <label class="control-label">Utilizador</label>
        <input type="text" class="form-control" value="<?= Html::encode($userNome) ?>" readonly>
    </div>

    <?= $form->field($model, 'data_encomenda')->input('datetime-local') ?>

    <?= $form->field($model, 'estado')->dropDownList([
        'Pendente' => 'Pendente',
        'Recebida' => 'Recebida',
        'Cancelada' => 'Cancelada',
    ], ['prompt' => '']) ?>

    <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacoes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'data_rececao')->input('datetime-local') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
