<?php

/** @var yii\web\View $this */
/** @var float $valorTotalStock */
/** @var int $materiaisSemStock */
/** @var int $materiaisAbaixoMinimo */
/** @var int $encomendasPendentes */
/** @var float $mediaEntrega */
/** @var \yii\data\ArrayDataProvider $topSaidasProvider */
/** @var \yii\data\ArrayDataProvider $ultimosMovimentosProvider */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'DashBoard';
?>
<div class="site-index">

    <!-- Top row: three equal boxes -->
    <div class="row" style="margin-bottom:8px;">
        <div class="col-md-4" style="padding-left:10px;padding-right:10px;">
            <div class="panel panel-default" style="height:220px;">
                <div class="panel-body text-center">
                    <h4><b>Valor Total em Stock</b></h4>
                    <div style="font-size:2.2em; margin:18px 0; color:#2d8659;">
                        <?= number_format($valorTotalStock, 2, ',', '.') ?> €
                    </div>
                    <hr>
                    <div>
                        <b><?= $materiaisSemStock ?></b> Sem stock<br>
                        <b><?= $materiaisAbaixoMinimo ?></b> Abaixo do stock mínimo
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="padding-left:10px;padding-right:10px;">
            <div class="panel panel-default" style="height:220px;">
                <div class="panel-body text-center">
                    <h4><b>Encomendas</b></h4>
                    <div style="font-size:2.2em; margin:18px 0; color:#b36b00;">
                        <?= $encomendasPendentes ?> Pendentes
                    </div>
                    <hr>
                    <div>
                        Média de entrega: <b><?= $mediaEntrega ?></b> dias
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="padding-left:10px;padding-right:10px;">
            <div class="panel panel-default" style="height:220px;">
                <div class="panel-body text-center">
                    <h4><b>Informação Geral</b></h4>
                    <div style="margin-top:30px;">
                        <span class="text-muted">Bem-vindo ao sistema de gestão de stock.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second row: left small, right large -->
    <div class="row" style="margin-top:6px;">
        <div class="col-md-4" style="padding-left:10px;padding-right:10px;">
            <div class="panel panel-default" style="height:350px;margin-bottom:6px;">
                <div class="panel-body">
                    <h5><b>Materiais com mais Saída</b></h5>
                    <?php
                    // Calcula o total para percentagens
                    $totalSaida = 0;
                    foreach ($topSaidasProvider->allModels as $row) {
                        $totalSaida += $row['total_saida'];
                    }
                    ?>
                    <?php if ($totalSaida > 0): ?>
                        <div>
                            <?php foreach ($topSaidasProvider->allModels as $row): 
                                $percent = round(($row['total_saida'] / $totalSaida) * 100);
                                ?>
                                <div style="margin-bottom:12px;">
                                    <div style="display:flex;justify-content:space-between;">
                                        <span><?= Html::encode($row['nome_material']) ?></span>
                                        <span><b><?= $row['total_saida'] ?></b></span>
                                    </div>
                                    <div style="background:#f1f3f4;border-radius:4px;height:18px;overflow:hidden;">
                                        <div style="
                                            background: linear-gradient(90deg, #0d6efd 60%, #6ea8fe 100%);
                                            width: <?= $percent ?>%;
                                            height: 100%;
                                            border-radius:4px;
                                            transition: width 0.3s;
                                        "></div>
                                    </div>
                                    <div style="font-size:0.95em;color:#666;text-align:right;">
                                        <?= $percent ?>%
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted" style="margin-top:40px;">
                            <i class="bi bi-box-seam" style="font-size:2.2em;opacity:.5;"></i><br>
                            <span style="font-size:1.1em;">Ainda não existem registos de saída</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-8" style="padding-left:10px;padding-right:10px;">
            <div class="panel panel-default" style="height:350px;margin-bottom:6px;">
                <div class="panel-body">
                    <h5><b>Últimos Movimentos</b></h5>
                    <?= GridView::widget([
                        'dataProvider' => $ultimosMovimentosProvider,
                        'summary' => '',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'material_id',
                                'label' => 'Material',
                                'value' => function($mov) {
                                    return $mov->material ? $mov->material->nome_material : '';
                                }
                            ],
                            [
                                'attribute' => 'tipo',
                                'label' => 'Tipo',
                                'format' => 'raw',
                                'value' => function($mov) {
                                    $tipo = strtolower($mov->tipo);
                                    $class = $tipo === 'entrada' ? 'tipo-entrada' : 'tipo-saida';
                                    return '<span class="tipo-badge ' . $class . '">' . Html::encode(ucfirst($mov->tipo)) . '</span>';
                                }
                            ],
                            [
                                'attribute' => 'quantidade',
                                'label' => 'Quantidade',
                            ],
                            [
                                'attribute' => 'data_movimentacao',
                                'label' => 'Data',
                            ],
                            [
                                'attribute' => 'user_id',
                                'label' => 'Utilizador',
                                'value' => function($mov) {
                                    return $mov->user ? $mov->user->username : '';
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>
