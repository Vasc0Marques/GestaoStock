<?php

namespace backend\controllers;

use Yii;
use common\models\EncomendaLinha;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EncomendaLinhaController implements the CRUD actions for EncomendaLinha model.
 */
class EncomendaLinhaController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['administrador', 'gestor'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all EncomendaLinha models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EncomendaLinha::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EncomendaLinha model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EncomendaLinha model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new EncomendaLinha();

        // Busca materiais do fornecedor da encomenda (para o dropdown)
        $materiais = [];
        $encomendaId = Yii::$app->request->get('encomenda_id') ?? $model->encomenda_id;
        if ($encomendaId) {
            $model->encomenda_id = $encomendaId;
            $encomenda = \common\models\Encomenda::findOne($encomendaId);
            if ($encomenda) {
                $materiais = \yii\helpers\ArrayHelper::map(
                    \common\models\Material::find()
                        ->innerJoin('materiais_fornecedores mf', 'mf.material_id = materiais.id')
                        ->where(['mf.fornecedor_id' => $encomenda->fornecedor_id])
                        ->groupBy('materiais.id')
                        ->orderBy('materiais.nome_material')
                        ->all(),
                    'id',
                    'nome_material'
                );
            }
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $material = \common\models\Material::findOne($model->material_id);
            $model->nome_material = $material ? $material->nome_material : '';

            $preco = \common\models\MaterialFornecedor::find()
                ->select('preco_base')
                ->where([
                    'material_id' => $model->material_id,
                    'fornecedor_id' => $model->encomenda->fornecedor_id
                ])
                ->scalar();

            $model->preco_unitario = is_numeric($preco) ? (float)$preco : 0.0;
            $model->subtotal = $model->preco_unitario * (int)$model->quantidade;

            if ($model->save(false)) {
                $this->atualizaTotalEncomenda($model->encomenda_id);
                return $this->redirect(['/encomenda/view', 'id' => $model->encomenda_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'materiais' => $materiais,
        ]);
    }

    /**
     * Updates an existing EncomendaLinha model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Busca materiais do fornecedor da encomenda (para o dropdown)
        $materiais = [];
        $encomenda = $model->encomenda;
        if ($encomenda) {
            $materiais = \yii\helpers\ArrayHelper::map(
                \common\models\Material::find()
                    ->innerJoin('materiais_fornecedores mf', 'mf.material_id = materiais.id')
                    ->where(['mf.fornecedor_id' => $encomenda->fornecedor_id])
                    ->groupBy('materiais.id')
                    ->orderBy('materiais.nome_material')
                    ->all(),
                'id',
                'nome_material'
            );
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $material = \common\models\Material::findOne($model->material_id);
            $materialFornecedor = \common\models\MaterialFornecedor::findOne([
                'material_id' => $model->material_id,
            ]);
            $model->nome_material = $material ? $material->nome_material : '';
            $preco = $materialFornecedor ? $materialFornecedor->preco_base : 0;
            $model->preco_unitario = is_numeric($preco) ? (float)$preco : 0.0;
            $model->subtotal = $model->preco_unitario * (int)$model->quantidade;

            if ($model->save(false)) {
                $this->atualizaTotalEncomenda($model->encomenda_id);
                return $this->redirect(['/encomenda/view', 'id' => $model->encomenda_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'materiais' => $materiais,
        ]);
    }

    /**
     * Deletes an existing EncomendaLinha model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $encomendaId = $model->encomenda_id;
        $model->delete();

        $this->atualizaTotalEncomenda($encomendaId);

        return $this->redirect(['/encomenda/view', 'id' => $encomendaId]);
    }

    /**
     * Finds the EncomendaLinha model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EncomendaLinha the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EncomendaLinha::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Atualiza o total da encomenda somando todos os subtotais das linhas.
     * @param int $encomendaId
     */
    protected function atualizaTotalEncomenda($encomendaId)
    {
        $encomenda = \common\models\Encomenda::findOne($encomendaId);
        if ($encomenda) {
            $total = (float) \common\models\EncomendaLinha::find()
                ->where(['encomenda_id' => $encomendaId])
                ->sum('subtotal');
            $encomenda->total = $total;
            $encomenda->save(false);
        }
    }
}
