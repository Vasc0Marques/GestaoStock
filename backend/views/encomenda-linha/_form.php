<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Material;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\EncomendaLinha $model */
/** @var yii\widgets\ActiveForm $form */

// Busca todos os materiais
$materiais = ArrayHelper::map(Material::find()->orderBy('nome_material')->all(), 'id', 'nome_material');

// Se a view foi chamada com encomenda_id na URL, preenche o modelo
if (empty($model->encomenda_id) && Yii::$app->request->get('encomenda_id')) {
    $model->encomenda_id = Yii::$app->request->get('encomenda_id');
}
?>

<div class="encomenda-linha-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'encomenda_id') ?>

    <?= $form->field($model, 'material_id')->dropDownList(
        $materiais,
        [
            'prompt' => 'Selecione o material...',
            'class' => 'form-control selectpicker',
            'data-live-search' => 'true'
        ]
    ) ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
