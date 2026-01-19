<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use common\models\Stock;

/** @var yii\web\View $this */
/** @var common\models\Material $model */
/** @var yii\data\ActiveDataProvider $movimentacoesDataProvider */

$this->title = $model->nome_material;
$this->params['breadcrumbs'][] = ['label' => 'Materiais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Buscar o stock atual do material
$stock = Stock::find()->where(['material_id' => $model->id])->one();
$stockAtual = $stock ? $stock->quantidade_atual : 0;
?>
<div class="row" style="min-height: 90vh;">
    <div class="col-md-5">
        <div class="material-view">
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['view', 'id' => $model->id],
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>
            <div style="width:200px;height:200px;margin:auto;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border-radius:8px;border:1px solid #ddd;position:relative; margin-bottom:44px;">
                <label for="material-foto-upload" style="width:100%;height:100%;margin:0;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    <img src="<?= $model->getImageUrlBackend() ?>"
                        alt="Foto do Material"
                        style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                    <?= Html::fileInput('Material[imagemFile]', null, [
                        'id' => 'material-foto-upload',
                        'accept' => 'image/*',
                        'style' => 'display:none;',
                    ]) ?>
                    <span style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.5);color:#fff;padding:2px 6px;border-radius:4px;font-size:12px;">Alterar foto</span>
                </label>
            </div>
            <!-- Informações editáveis -->
            <div class="mt-3 mb-3">
                <?= \yii\widgets\DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => 'Categoria',
                            'value' => $model->categoria ? $model->categoria->nome_categoria : '',
                        ],
                        [
                            'label' => 'Zona',
                            'value' => $model->zona ? $model->zona->nome_zona : '',
                        ],
                        'nome_material',
                        'codigo',
                        'unidade_medida',
                        'stock_minimo',
                        [
                            'label' => 'Stock Atual',
                            'value' => $stockAtual,
                        ],
                        'criado_em',
                        // ...adicione outros atributos se necessário...
                    ],
                ]) ?>
            </div>
            <!-- Botões -->
            <div class="text-center mb-3">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Apagar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Tem a certeza que pretende apagar este item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-md-7" style="display: flex; flex-direction: column; height: 85vh;">
        <div class="panel panel-default" style="flex: 1 1 auto; display: flex; flex-direction: column; height: 100%;">
            <div class="panel-heading"><b>Movimentações</b></div>
            <div class="panel-body" style="flex: 1 1 auto; overflow-y: auto;">
                <?= GridView::widget([
                    'dataProvider' => $movimentacoesDataProvider,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'attribute' => 'tipo',
                            'label' => 'Tipo',
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'kv-align-center'],
                            'value' => function($model) {
                                $tipo = strtolower($model->tipo);
                                $class = $tipo === 'entrada' ? 'tipo-entrada' : 'tipo-saida';
                                return '<span class="tipo-badge ' . $class . '">' . Html::encode(ucfirst($model->tipo)) . '</span>';
                            }
                        ],
                        'quantidade',
                        'data_movimentacao',
                        [
                            'attribute' => 'user_id',
                            'label' => 'Utilizador',
                            'value' => function($model) {
                                return $model->user ? $model->user->getNomeCompleto() : '';
                            }
                        ],
                    ],
                    'panel' => false,
                    'toolbar' => false,
                    'summary' => '',
                ]); ?>
            </div>
        </div>
    </div>
</div>
