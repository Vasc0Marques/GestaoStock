<?php

namespace backend\controllers;

use common\models\EncomendaLinha;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EncomendaLinhaController implements the CRUD actions for EncomendaLinha model.
 */
class EncomendaLinhaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $material = \common\models\Material::findOne($model->material_id);
                if ($material) {
                    $model->nome_material = $material->nome_material;
                    $preco = null;
                    $matFornecedor = \common\models\MaterialFornecedor::find()
                        ->where(['material_id' => $model->material_id, 'fornecedor_id' => $model->encomenda->fornecedor_id])
                        ->one();
                    if ($matFornecedor && $matFornecedor->preco_base > 0) {
                        $preco = $matFornecedor->preco_base;
                    } elseif ($material->preco_base > 0) {
                        $preco = $material->preco_base;
                    } else {
                        $preco = 0;
                    }
                    $model->preco_unitario = $preco;
                } else {
                    $model->preco_unitario = 0;
                }
                if ($model->save(false)) {
                    // Atualiza o total da encomenda
                    $this->atualizaTotalEncomenda($model->encomenda_id);
                    return $this->redirect(['/encomenda/view', 'id' => $model->encomenda_id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
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

        if ($this->request->isPost && $model->load($this->request->post())) {
            $material = \common\models\Material::findOne($model->material_id);
            if ($material) {
                $model->nome_material = $material->nome_material;
                $preco = null;
                $matFornecedor = \common\models\MaterialFornecedor::find()
                    ->where(['material_id' => $model->material_id, 'fornecedor_id' => $model->encomenda->fornecedor_id])
                    ->one();
                if ($matFornecedor && $matFornecedor->preco_base > 0) {
                    $preco = $matFornecedor->preco_base;
                } elseif ($material->preco_base > 0) {
                    $preco = $material->preco_base;
                } else {
                    $preco = 0;
                }
                $model->preco_unitario = $preco;
            } else {
                $model->preco_unitario = 0;
            }
            if ($model->save(false)) {
                // Atualiza o total da encomenda
                $this->atualizaTotalEncomenda($model->encomenda_id);
                return $this->redirect(['/encomenda/view', 'id' => $model->encomenda_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
