<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Fornecedor $model */

$this->title = $model->nome_fornecedor;
$this->params['breadcrumbs'][] = ['label' => 'Fornecedors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// Busca materiais do fornecedor
$materiaisProvider = new \yii\data\ActiveDataProvider([
    'query' => $model->getMateriaisFornecedores()->with('material'),
    'pagination' => false,
]);
?>
<div class="fornecedor-view">
    <div class="row">
        <div class="col-md-4 text-center">
            <?php $form = ActiveForm::begin([
                'action' => ['update', 'id' => $model->id],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'enctype' => 'multipart/form-data'],
            ]); ?>
            <!-- Box fixa para foto com upload -->
            <div style="width:200px;height:200px;margin:auto;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border-radius:8px;border:1px solid #ddd;position:relative;">
                <label for="fornecedor-foto-upload" style="width:100%;height:100%;margin:0;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    <img src="<?= $model->foto_url ?? 'https://via.placeholder.com/150?text=Foto+Fornecedor' ?>"
                         alt="Foto do Fornecedor"
                         style="max-width:140px;max-height:140px;border-radius:8px;">
                    <input type="file" id="fornecedor-foto-upload" name="Fornecedor[foto]" accept="image/*" style="display:none;">
                    <span style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.5);color:#fff;padding:2px 6px;border-radius:4px;font-size:12px;">Alterar foto</span>
                </label>
            </div>
            <!-- Informações editáveis -->
            <div class="mt-3 mb-3">
                <table class="table table-bordered" style="margin:30px;">
                    <tbody>
                        <tr>
                            <th style="text-align:left;">Nome</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'nome_fornecedor', [
                                    'template' => '{input}',
                                ])->textInput(['class' => 'form-control w-100', 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">NIF</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'nif', [
                                    'template' => '{input}',
                                ])->textInput(['class' => 'form-control w-100', 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Telefone</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'telefone', [
                                    'template' => '{input}',
                                ])->textInput(['class' => 'form-control w-100', 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Email</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'email', [
                                    'template' => '{input}',
                                ])->textInput(['class' => 'form-control w-100', 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Morada</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'morada', [
                                    'template' => '{input}',
                                ])->textarea(['class' => 'form-control w-100', 'rows' => 1, 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Ativo</th>
                            <td style="text-align:left;">
                                <?= $form->field($model, 'ativo', [
                                    'template' => '{input}',
                                ])->dropDownList(['1' => 'Sim', '0' => 'Não'], ['class' => 'form-control w-100', 'style' => 'width:220px;text-align:left;']) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Botões -->
            <div class="mb-3">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja eliminar este fornecedor?',
                        'method' => 'post',
                        'pjax' => 0,
                    ],
                    'onclick' => "setTimeout(function(){ $('.modal-backdrop').remove(); }, 200);"
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-8">
            <?= GridView::widget([
                'dataProvider' => $materiaisProvider,
                'panelFooterTemplate' => '{footer}',
                'containerOptions' => ['style' => 'height: 440px !important;'],
                'columns' => [
                    [
                        'attribute' => 'material_id',
                        'label' => 'ID',
                        'contentOptions' => ['style' => 'width:80px;text-align:center;'],
                        'headerOptions' => ['style' => 'width:80px;text-align:center;'],
                        'value' => function($mf) { return $mf->material_id; }
                    ],
                    [
                        'attribute' => 'material.nome_material',
                        'label' => 'Nome Material',
                        'contentOptions' => ['style' => 'width:220px;'],
                        'headerOptions' => ['style' => 'width:220px;'],
                        'value' => function($mf) { return $mf->material ? $mf->material->nome_material : ''; }
                    ],
                    [
                        'attribute' => 'preco_base',
                        'label' => 'Preço Base',
                        'contentOptions' => ['style' => 'width:100px;text-align:center;'],
                        'headerOptions' => ['style' => 'width:100px;text-align:center;'],
                        'value' => function($mf) {
                            return $mf->preco_base . ' €';
                        }
                    ],
                    [
                        'attribute' => 'prazo_entrega_dias',
                        'label' => 'Prazo Entrega',
                        'contentOptions' => ['style' => 'width:100px;text-align:center;'],
                        'headerOptions' => ['style' => 'width:100px;text-align:center;'],
                        'value' => function($mf) {
                            return $mf->prazo_entrega_dias . ' dias';
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'urlCreator' => function ($action, $mf, $key, $index) {
                            return [$action === 'update' ? 'material-fornecedor/update' : $action, 'id' => $mf->id];
                        },
                        'buttons' => [
                            'update' => function ($url, $mf, $key) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                    'title' => 'Editar',
                                    'class' => 'btn btn-sm btn-primary',
                                ]);
                            },
                            'delete' => function ($url, $mf, $key) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => 'Eliminar',
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => 'Tem a certeza que deseja eliminar este material do fornecedor?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ],
                    ],
                ],
                'toolbar' => [],
                'panel' => [
                    'type' => \kartik\grid\GridView::TYPE_DEFAULT,
                    'heading' => 'Materiais deste fornecedor',
                    'footer' => Html::a('Adicionar Material', ['material-fornecedor/create', 'fornecedor_id' => $model->id], ['class' => 'btn btn-success']),
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
