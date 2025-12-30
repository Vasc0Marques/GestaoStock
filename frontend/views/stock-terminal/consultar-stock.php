<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

$this->title = 'Consultar Stock';
?>
<div class="container-fluid" style="padding: 20px; min-height: 100vh;">
    <div class="row" style="display: flex; flex-wrap: nowrap; height: 82vh;">
        <!-- Lado esquerdo: QR, input e botão -->
        <div class="col-md-4" style="border:1px solid #ccc; border-radius:8px; background:#fafbfc; min-height:0; max-width:260px; flex: 0 0 260px; margin-right: 24px; height: 100%; display: flex; align-items: flex-start;">
            <div style="width:100%; padding:32px 12px 12px 12px; text-align:center;">
                <!-- Simulação de código de barras -->
                <div style="margin-bottom:32px;">
                    <img src="https://barcode.tec-it.com/barcode.ashx?data=C61231&code=Code128&translate-esc=on" alt="Código de Barras" style="max-width:100%;height:60px;">
                </div>
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['stock-terminal/consultar'],
                    'options' => ['style' => 'margin-bottom:0;'],
                ]); ?>
                <div style="margin-bottom:16px;">
                    <?= Html::input('text', 'codigo', Yii::$app->request->get('codigo'), [
                        'class' => 'form-control',
                        'placeholder' => 'Código do material',
                        'style' => 'text-align:center; font-size:1em; padding:6px 8px;',
                    ]) ?>
                </div>
                <div>
                    <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary', 'style' => 'width:100%; font-size:1em; padding:6px 0;']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- Lado direito: tabela de materiais em stock -->
        <div class="col-md-8" style="border:1px solid #ccc; border-radius:8px; background:#fff; min-height:0; flex: 1 1 0; height: 100%; display: flex; flex-direction: column; padding:0;">
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