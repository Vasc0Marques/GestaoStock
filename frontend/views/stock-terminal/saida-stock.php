<?php

/** @var yii\web\View $this */
/** @var \common\models\Material|null $material */
/** @var int|null $stockAtual */
/** @var \yii\data\ActiveDataProvider|null $movDataProvider */
/** @var array|null $mensagem */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

$this->title = 'Saída Stock';
?>
<div class="container-fluid" style="padding: 20px; min-height: 90vh;">
    <?php if (isset($mensagem) && $mensagem): ?>
        <div class="alert alert-<?= $mensagem[0] ?>" style="font-size:1.1em; margin-bottom:18px;">
            <?= htmlspecialchars($mensagem[1]) ?>
        </div>
    <?php endif; ?>
    <div class="row" style="display: flex; flex-wrap: nowrap; height: 82vh;">
        <!-- Box esquerda: pesquisa material -->
        <div class="col-md-4" style="border:1px solid #ccc; border-radius:8px; background:#fafbfc; min-height:0; max-width:310px; flex: 0 0 310px; margin-right: 24px; height: 100%; display: flex; align-items: center; justify-content: center;">
            <div style="width:100%; padding:32px 12px 12px 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                <div style="margin-bottom:32px; display: flex; justify-content: center; width: 100%;">
                    <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= Html::encode($material ? $material->codigo : 'C61231') ?>&code=Code128&translate-esc=on" alt="Código de Barras" style="max-width:100%;height:100px; margin: 0 auto;">
                </div>
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['stock-terminal/saida'],
                    'options' => ['style' => 'margin-bottom:0;'],
                ]); ?>
                <div style="margin-bottom:16px; display: flex; justify-content: center; width: 100%;">
                    <?= Html::input('text', 'codigo', $material ? $material->codigo : '', [
                        'class' => 'form-control',
                        'placeholder' => 'Código do material',
                        'style' => 'text-align:center; font-size:1.3em; padding:10px 16px; margin: 0 auto; max-width: 270px; height: 48px;',
                    ]) ?>
                </div>
                <div style="display: flex; justify-content: center; width: 100%;">
                    <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary', 'style' => 'width:100%; max-width:200px; font-size:1em; padding:6px 0; margin: 0 auto;']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- Box direita: tudo o resto dentro de uma box -->
        <div class="col-md-8" style="border:1px solid #ccc; border-radius:8px; background:#fff; min-height:0; flex: 1 1 0; height: 100%; display: flex; flex-direction: column; padding:0;">
            <div style="flex: 1 1 0; display: flex; flex-direction: column; height: 100%; padding:0;">
                <div style="height:100%; border:1px solid #bbb; border-radius:8px; background:#fff; padding:18px; display:flex; flex-direction:column;">
                <!-- Header com nome do material -->
                <div style="font-weight:bold; font-size:1.2em; margin-bottom:16px;">
                    <?= $material ? Html::encode($material->nome_material) : 'Material' ?>
                </div>
                <div style="display:flex; flex-wrap:nowrap; gap:24px; flex:1 1 0; height:100%;">
                    <!-- Coluna central: imagem e saída -->
                    <div style="flex:0 0 320px; max-width:320px; display:flex; flex-direction:column; gap:18px; padding:0; height:100%;">
                        <!-- Imagem do material -->
                        <div style="border:1px solid #ccc; border-radius:6px; background:#f4f4f4; width:240px; height:180px; display:flex; align-items:center; justify-content:center; margin:auto;">
                            <img src="<?= $material ? $material->getImageUrlFrontend() : '/gestaostock/backend/web/img/noImage.jpg' ?>"
                                alt="Foto do Material"
                                style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                        </div>
                        <!-- Input e botão para saída de stock -->
                        <div style="border:1px solid #ccc; border-radius:6px; background:#fafbfc; padding:12px; margin-top:auto;">
                            <?php if ($material): ?>
                                <?= Html::beginForm(['stock-terminal/saida'], 'post') ?>
                                <div style="margin-bottom:8px;">
                                    <?= Html::label('Quantidade a sair', 'quantidade_saida', ['style' => 'font-weight:500;']) ?>
                                    <?= Html::input('number', 'quantidade_saida', '', [
                                        'class' => 'form-control',
                                        'min' => 1,
                                        'max' => $stockAtual !== null ? $stockAtual : null,
                                        'style' => 'margin-top:4px;',
                                    ]) ?>
                                </div>
                                <?= Html::hiddenInput('codigo', $material->codigo) ?>
                                <?= Html::submitButton('Registar Saída', ['class' => 'btn btn-danger', 'style' => 'width:100%;']) ?>
                                <?= Html::endForm() ?>
                            <?php else: ?>
                                <div class="text-muted" style="font-size:1.1em;">Pesquise um material para registar saída.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Coluna detalhes do material -->
                    <div style="flex:1 1 0; display:flex; flex-direction:column; gap:18px; height:100%;">
                        <div style="border:1px solid #ccc; border-radius:6px; background:#fafbfc; padding:18px; min-height:180px; margin-bottom:12px;">
                            <?php if ($material): ?>
                                <p><b>Código:</b> <?= Html::encode($material->codigo) ?></p>
                                <p><b>Unidade:</b> <?= Html::encode($material->unidade_medida) ?></p>
                                <p><b>Stock Atual:</b> <?= $stockAtual !== null ? Html::encode($stockAtual) : 'N/A' ?></p>
                                <p><b>Stock Mínimo:</b> <?= Html::encode($material->stock_minimo) ?></p>
                                <p><b>Zona:</b> <?= Html::encode($material->zona ? $material->zona->nome_zona : '') ?></p>
                                <p><b>Categoria:</b> <?= Html::encode($material->categoria ? $material->categoria->nome_categoria : '') ?></p>
                            <?php else: ?>
                                <div class="text-muted" style="font-size:1.1em;">Pesquise um material pelo código para ver os detalhes.</div>
                            <?php endif; ?>
                        </div>
                        <!-- Tabela de movimentações -->
                        <div style="flex:1 1 0; display:flex; flex-direction:column; min-height:0;">
                            <div style="font-weight:bold; margin-bottom:8px;">Histórico de Movimentações</div>
                            <div style="border:1px solid #ccc; border-radius:6px; background:#fafbfc; padding:0; flex:1 1 0; min-height:0; overflow:auto;">
                                <?php if ($material && $movDataProvider): ?>
                                    <?= GridView::widget([
                                        'dataProvider' => $movDataProvider,
                                        'summary' => '',
                                        'bordered' => true,
                                        'striped' => true,
                                        'hover' => true,
                                        'condensed' => false,
                                        'columns' => [
                                            ['class' => 'kartik\grid\SerialColumn'],
                                            [
                                                'attribute' => 'data_movimentacao',
                                                'label' => 'Data',
                                            ],
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
                                            [
                                                'attribute' => 'quantidade',
                                                'label' => 'Quantidade',
                                            ],
                                            [
                                                'attribute' => 'origem',
                                                'label' => 'Origem',
                                            ],
                                            [
                                                'attribute' => 'user_id',
                                                'label' => 'Utilizador',
                                                'value' => function($model) {
                                                    return $model->user ? $model->user->username : '';
                                                }
                                            ],
                                        ],
                                        'panel' => false,
                                        'toolbar' => false,
                                        'tableOptions' => [
                                            'style' => 'font-size:1em;',
                                        ],
                                        'options' => [
                                            'style' => 'width:100%;',
                                        ],
                                    ]); ?>
                                <?php else: ?>
                                    <div class="text-muted" style="padding:18px;">Nenhum histórico para exibir.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>