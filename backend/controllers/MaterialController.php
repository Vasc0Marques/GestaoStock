<?php

namespace backend\controllers;

use common\models\Material;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\Categoria;
use common\models\Zona;

/**
 * MaterialController implements the CRUD actions for Material model.
 */
class MaterialController extends Controller
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
                            'roles' => ['gestor'],
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
     * Lists all Material models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Material::find(),

        ]);

        $categoria = Categoria::find()->all();
        $zonas = Zona::find()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categorias' => $categoria,
            'zonas' => $zonas,
        ]);
    }

    /**
     * Displays a single Material model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $movimentacoesDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $model->getMovimentacoes()->with('user'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'movimentacoesDataProvider' => $movimentacoesDataProvider,
        ]);
    }

    /**
     * Creates a new Material model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Material();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        // Carregar categorias e zonas do banco de dados
        $categorias = ArrayHelper::map(Categoria::find()->orderBy('nome_categoria')->all(), 'id', 'nome_categoria');
        $zonas = ArrayHelper::map(Zona::find()->orderBy('nome_zona')->all(), 'id', 'nome_zona');

        return $this->render('create', [
            'model' => $model,
            'categorias' => $categorias,
            'zonas' => $zonas,
        ]);
    }

    /**
     * Updates an existing Material model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->imagemFile = \yii\web\UploadedFile::getInstance($model, 'imagemFile');
            if ($model->imagemFile) {
                $dir = \Yii::getAlias('@backend/web/uploads');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName = 'material_' . $model->id . '_' . time() . '.' . $model->imagemFile->extension;
                $filePath = $dir . DIRECTORY_SEPARATOR . $fileName;
                if ($model->imagemFile->saveAs($filePath)) {
                    $model->imagem = 'uploads/' . $fileName;
                }
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Carregar categorias e zonas do banco de dados
        $categorias = ArrayHelper::map(Categoria::find()->orderBy('nome_categoria')->all(), 'id', 'nome_categoria');
        $zonas = ArrayHelper::map(Zona::find()->orderBy('nome_zona')->all(), 'id', 'nome_zona');

        return $this->render('update', [
            'model' => $model,
            'categorias' => $categorias,
            'zonas' => $zonas,
        ]);
    }

    /**
     * Deletes an existing Material model.
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
     * Finds the Material model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Material the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Material::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
