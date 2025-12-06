<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Material;
use common\models\Fornecedor;

/** @var yii\web\View $this */
/** @var common\models\MaterialFornecedor $model */
/** @var yii\widgets\ActiveForm $form */

// Busca todos os materiais
$materiais = \yii\helpers\ArrayHelper::map(Material::find()->orderBy('nome_material')->all(), 'id', 'nome_material');

// Busca todos os fornecedores
$fornecedores = \yii\helpers\ArrayHelper::map(Fornecedor::find()->orderBy('nome_fornecedor')->all(), 'id', 'nome_fornecedor');
?>

<div class="material-fornecedor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'material_id')->dropDownList(
        $materiais,
        [
            'prompt' => 'Selecione o material...',
            'class' => 'form-control selectpicker',
            'data-live-search' => 'true'
        ]
    ) ?>

    <?= $form->field($model, 'fornecedor_id')->hiddenInput(['value' => $model->fornecedor_id ?: Yii::$app->request->get('fornecedor_id')])->label(false) ?>

    <div class="form-group">
        <label class="control-label">Fornecedor</label>
        <input type="text" class="form-control"
            value="<?= isset($fornecedores[$model->fornecedor_id ?: Yii::$app->request->get('fornecedor_id')]) ? Html::encode($fornecedores[$model->fornecedor_id ?: Yii::$app->request->get('fornecedor_id')]) : '' ?>"
            readonly>
    </div>

    <?= $form->field($model, 'preco_base')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prazo_entrega_dias')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
