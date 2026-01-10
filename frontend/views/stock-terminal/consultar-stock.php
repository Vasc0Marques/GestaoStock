<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

$this->title = 'Consultar Stock';
?>
<div class="stock-terminal-container">
    <div class="stock-terminal-row">
        <!-- Lado esquerdo: QR, input e botão -->
        <div class="stock-terminal-left">
            <div class="stock-terminal-left-content">
                <!-- Simulação de código de barras -->
                <div class="stock-terminal-barcode">
                    <img src="https://barcode.tec-it.com/barcode.ashx?data=C61231&code=Code128&translate-esc=on" alt="Código de Barras">
                </div>
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['stock-terminal/consultar'],
                    'options' => ['style' => 'margin-bottom:0;'],
                ]); ?>
                <div class="stock-terminal-input">
                    <?= Html::input('text', 'codigo', Yii::$app->request->get('codigo'), [
                        'class' => 'form-control',
                        'placeholder' => 'Código do material',
                    ]) ?>
                </div>
                <div class="stock-terminal-button">
                    <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- Lado direito: tabela de materiais em stock -->
        <div class="stock-terminal-right">
            <div style="flex: 1 1 0; display: flex; flex-direction: column; height: 100%; padding:0;">
                <div class="panel panel-default" style="flex:1 1 0; min-height:0; display:flex; flex-direction:column; height:100%; padding:0 18px 18px 18px;">
                    <div class="panel-body" style="flex:1 1 0; min-height:0; display:flex; flex-direction:column; height:100%; padding:10px 0;">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'panelFooterTemplate' => '{footer}',
                            'containerOptions' => ['style' => 'height: 100% !important;'],
                            'columns' => [
                                ['class' => 'kartik\grid\SerialColumn'],
                                [
                                    'attribute' => 'material_id',
                                    'label' => 'Material',
                                    'value' => function($model) {
                                        return $model->material ? $model->material->nome_material : '';
                                    }
                                ],
                                [
                                    'attribute' => 'codigo',
                                    'label' => 'Código',
                                    'value' => function($model) {
                                        return $model->material ? $model->material->codigo : '';
                                    }
                                ],
                                [
                                    'attribute' => 'quantidade_atual',
                                    'label' => 'Stock Atual',
                                ],
                                [
                                    'attribute' => 'ultima_atualizacao',
                                    'label' => 'Última Atualização',
                                ],
                            ],
                            'toolbar' => [],
                            'panel' => [
                                'type' => \kartik\grid\GridView::TYPE_DEFAULT,
                                'heading' => $this->title,
                            ],
                            'export' => [
                                'fontAwesome' => true
                            ],
                            'toggleDataOptions' => [
                                'minCount' => 10
                            ],
                            'responsive' => true,
                            'hover' => true,
                            'summary' => 'Mostrando {begin}-{end} de {totalCount} materiais.',
                            'rowOptions' => function($model) {
                                $codigo = $model->material ? $model->material->codigo : '';
                                return [
                                    'ondblclick' => "window.location='" . \yii\helpers\Url::to(['stock-terminal/saida', 'codigo' => $codigo]) . "'",
                                    'style' => 'cursor:pointer;',
                                ];
                            },
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>