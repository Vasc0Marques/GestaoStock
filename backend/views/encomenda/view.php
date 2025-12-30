<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use common\models\EncomendaLinha;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */

$this->title = 'Encomenda #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Encomendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// DataProvider para as linhas da encomenda
$linhasProvider = new \yii\data\ActiveDataProvider([
    'query' => $model->getEncomendaLinhas()->with('material'),
    'pagination' => false,
]);
?>
<div class="encomenda-view">
    <div class="row">
        <div class="col-md-4 text-center">
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['update', 'id' => $model->id],
                'method' => 'post',
                'options' => ['class' => 'form-inline'],
            ]); ?>
            <div class="mt-3 mb-3">
                <table class="table table-bordered" style="margin:30px;">
                    <tbody>
                        <tr>
                            <th style="text-align:left;">ID</th>
                            <td style="text-align:left;">
                                <?= Html::encode($model->id) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fornecedor</th>
                            <td style="text-align:left;">
                                <input type="text" class="form-control w-100" style="width:220px;text-align:left;" value="<?= $model->fornecedor ? Html::encode($model->fornecedor->nome_fornecedor) : '' ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Utilizador</th>
                            <td style="text-align:left;">
                                <input type="text" class="form-control w-100" style="width:220px;text-align:left;" value="<?= $model->user ? Html::encode($model->user->username . (isset($model->user->apelido) ? ' ' . $model->user->apelido : '')) : '' ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Data Encomenda</th>
                            <td style="text-align:left;">
                                <input type="date"
                                       class="form-control w-100"
                                       style="width:220px;text-align:left;"
                                       name="Encomenda[data_encomenda]"
                                       value="<?= !empty($model->data_encomenda) ? date('Y-m-d', strtotime($model->data_encomenda)) : '' ?>">
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Estado</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'estado', [
                                    'template' => '{input}',
                                ])->dropDownList([
                                    'Pendente' => 'Pendente',
                                    'Recebida' => 'Recebida',
                                    'Cancelada' => 'Cancelada',
                                ], ['class' => 'form-control w-100', 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Total</th>
                            <td style="text-align:left;">
                                <input type="text" class="form-control w-100" style="width:220px;text-align:left;" value="<?= Html::encode($model->total) ?> €" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Observações</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'observacoes', [
                                    'template' => '{input}',
                                ])->textarea(['class' => 'form-control w-100', 'rows' => 2, 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Data Receção</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'data_rececao', [
                                    'template' => '{input}',
                                ])->input(
                                    'date',
                                    [
                                        'class' => 'form-control w-100',
                                        'style' => 'width:220px;text-align:left;',
                                        'value' => !empty($model->data_rececao)
                                            ? date('Y-m-d', strtotime($model->data_rececao))
                                            : ''
                                    ]
                                ) ?>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-3">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja eliminar esta encomenda?',
                        'method' => 'post',
                        'pjax' => 0,
                    ],
                    'onclick' => "setTimeout(function(){ $('.modal-backdrop').remove(); }, 200);"
                ]) ?>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
        <div class="col-md-8">
            <?= GridView::widget([
                'dataProvider' => $linhasProvider,
                'panelFooterTemplate' => '{footer}',
                'containerOptions' => ['style' => 'height: 440px !important;'],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => 'ID',
                        'contentOptions' => ['style' => 'width:60px;text-align:center;'],
                        'headerOptions' => ['style' => 'width:60px;text-align:center;'],
                    ],
                    [
                        'attribute' => 'material_id',
                        'label' => 'Material',
                        'value' => function($linha) {
                            return $linha->material ? $linha->material->nome_material : '';
                        },
                        'contentOptions' => ['style' => 'width:180px;'],
                        'headerOptions' => ['style' => 'width:180px;'],
                    ],
                    [
                        'attribute' => 'quantidade',
                        'label' => 'Quantidade',
                        'contentOptions' => ['style' => 'width:100px;text-align:center;'],
                        'headerOptions' => ['style' => 'width:100px;text-align:center;'],
                    ],
                    [
                        'attribute' => 'preco_unitario',
                        'label' => 'Preço Unitário',
                        'value' => function($linha) {
                            return $linha->preco_unitario . ' €';
                        },
                        'contentOptions' => ['style' => 'width:120px;text-align:center;'],
                        'headerOptions' => ['style' => 'width:120px;text-align:center;'],
                    ],
                    [
                        'attribute' => 'subtotal',
                        'label' => 'Subtotal',
                        'value' => function($linha) {
                            return $linha->subtotal . ' €';
                        },
                        'contentOptions' => ['style' => 'width:120px;text-align:center;'],
                        'headerOptions' => ['style' => 'width:120px;text-align:center;'],
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'controller' => 'encomenda-linha',
                        'urlCreator' => function ($action, $linha, $key, $index) {
                            // Corrige para sempre usar o controller encomenda-linha
                            return [$action === 'update' ? 'encomenda-linha/update' : 'encomenda-linha/delete', 'id' => $linha->id];
                        },
                        'buttons' => [
                            'update' => function ($url, $linha, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => 'Editar',
                                    'class' => 'btn btn-sm btn-primary',
                                ]);
                            },
                            'delete' => function ($url, $linha, $key) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => 'Eliminar',
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar esta linha?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
                'toolbar' => [],
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                    'heading' => 'Linhas da Encomenda',
                    'footer' => Html::a('Adicionar Linha', ['encomenda-linha/create', 'encomenda_id' => $model->id], ['class' => 'btn btn-success']),
                ],
                'export' => [
                    'fontAwesome' => true
                ],
                'toggleDataOptions' => [
                    'minCount' => 10
                ],
            ]) ?>
        </div>
    </div>
</div>
