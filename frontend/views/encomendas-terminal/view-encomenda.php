<?php
/** @var yii\web\View $this */
/** @var \common\models\Encomenda $model */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Encomenda #' . $model->id;
?>
<style>
.estado-badge {
    display: inline-block;
    padding: 0.35em 0.9em;
    font-size: 1em;
    font-weight: 600;
    border-radius: 0.7em;
    border: 2px solid transparent;
    background: #f8f9fa;
    min-width: 90px;
    text-align: center;
    margin: 0 auto;
}
.estado-pendente {
    color: #b36b00;
    border-color: #fd7e14;
    background: #fff7e6;
}
.estado-recebida {
    color: #198754;
    border-color: #198754;
    background: #e9fbe8;    
}
.estado-cancelada {
    color: #dc3545;
    border-color: #dc3545;
    background: #fbeaea;
}
.info-label {
    font-weight: 500;
    color: #444;
    width: 130px;
    vertical-align: middle;
}
.info-value {
    color: #222;
    vertical-align: middle;
}
.icon {
    margin-right: 8px;
    opacity: 0.7;
}
@media (max-width: 900px) {
    .col-md-4, .col-md-8 { max-width: 100% !important; flex: 100% !important; }
}
</style>
<div class="container-fluid" style="padding: 20px; min-height: 90vh;">
    <div class="row" style="height: 82vh; display: flex; align-items: center; justify-content: center;">
        <div class="col-md-4" style="display: flex; align-items: center; justify-content: center; height: 100%;">
            <div class="panel panel-default" style="padding:24px 18px; margin:auto; min-width:420px; max-width:540px; border-radius:10px; box-shadow:0 2px 12px #0001;">
                <h4 style="margin-bottom:24px;">
                    <span class="icon"><i class="fa fa-file-text-o"></i></span>
                    <b>Detalhes da Encomenda</b>
                </h4>
                <table class="table table-borderless" style="margin-bottom:0;">
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-hashtag"></i></span>ID</td>
                        <td class="info-value"><?= Html::encode($model->id) ?></td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-truck"></i></span>Fornecedor</td>
                        <td class="info-value"><?= $model->fornecedor ? Html::encode($model->fornecedor->nome_fornecedor) : '' ?></td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-user"></i></span>Utilizador</td>
                        <td class="info-value"><?= $model->user ? Html::encode($model->user->username) : '' ?></td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-calendar"></i></span>Data</td>
                        <td class="info-value"><?= Html::encode($model->data_encomenda) ?></td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-info-circle"></i></span>Estado</td>
                        <td class="info-value">
                            <?php
                            $estado = strtolower($model->estado);
                            $map = [
                                'pendente' => 'estado-pendente',
                                'recebida' => 'estado-recebida',
                                'cancelada' => 'estado-cancelada',
                            ];
                            $class = isset($map[$estado]) ? $map[$estado] : 'estado-badge';
                            ?>
                            <span class="estado-badge <?= $class ?>">
                                <?= Html::encode($model->estado) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-eur"></i></span>Total</td>
                        <td class="info-value"><b><?= Html::encode($model->total) ?> €</b></td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-comment"></i></span>Observações</td>
                        <td class="info-value"><?= Html::encode($model->observacoes) ?></td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="icon"><i class="fa fa-calendar-check-o"></i></span>Data Receção</td>
                        <td class="info-value"><?= Html::encode($model->data_rececao) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-8" style="height: 100%; display: flex; flex-direction: column;">
            <div class="panel panel-default" style="padding:24px 18px; flex: 1 1 0; height: 100%; display: flex; flex-direction: column; border-radius:10px; box-shadow:0 2px 12px #0001;">
                <h4 style="margin-bottom:18px;">
                    <span class="icon"><i class="fa fa-list"></i></span>
                    <b>Linhas da Encomenda</b>
                </h4>
                <div style="flex:1 1 0; min-height:0; overflow-y:auto;">
                    <?= GridView::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'allModels' => $model->encomendaLinhas,
                            'pagination' => false,
                        ]),
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            [
                                'attribute' => 'material_id',
                                'label' => 'Material',
                                'value' => function($linha) {
                                    return $linha->material ? $linha->material->nome_material : $linha->nome_material;
                                }
                            ],
                            [
                                'attribute' => 'quantidade',
                                'label' => 'Quantidade',
                            ],
                            [
                                'attribute' => 'preco_unitario',
                                'label' => 'Preço Unitário',
                                'value' => function($linha) {
                                    return $linha->preco_unitario . ' €';
                                }
                            ],
                            [
                                'attribute' => 'subtotal',
                                'label' => 'Subtotal',
                                'value' => function($linha) {
                                    return $linha->subtotal . ' €';
                                }
                            ],
                        ],
                        'panel' => false,
                        'toolbar' => false,
                        'summary' => '',
                        'options' => ['style' => 'height:100%;'],
                        'tableOptions' => ['style' => 'font-size:1em;'],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
