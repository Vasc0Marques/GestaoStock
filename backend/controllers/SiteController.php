<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller - NÃO herda de BaseController para permitir acesso a login/access-denied
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'access-denied'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['administrador', 'gestor'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect(['site/access-denied']);
                },
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Valor total do stock
        $valorTotalStock = (new \yii\db\Query())
            ->select(['SUM(s.quantidade_atual * mf.preco_base) AS total'])
            ->from(['s' => 'stock'])
            ->leftJoin('materiais_fornecedores mf', 'mf.material_id = s.material_id')
            ->scalar();
        $valorTotalStock = $valorTotalStock ?: 0;

        // Materiais sem stock
        $materiaisSemStock = \common\models\Material::find()
            ->leftJoin('stock', 'stock.material_id = materiais.id')
            ->where(['or', ['stock.quantidade_atual' => null], ['<=', 'stock.quantidade_atual', 0]])
            ->count();

        // Materiais abaixo do stock mínimo
        $materiaisAbaixoMinimo = \common\models\Material::find()
            ->leftJoin('stock', 'stock.material_id = materiais.id')
            ->where('stock.quantidade_atual < materiais.stock_minimo')
            ->andWhere('stock.quantidade_atual IS NOT NULL')
            ->count();

        // Encomendas pendentes
        $encomendasPendentes = \common\models\Encomenda::find()->where(['estado' => 'Pendente'])->count();

        // Média de entrega (dias) das encomendas recebidas
        $mediaEntrega = (new \yii\db\Query())
            ->select(['AVG(DATEDIFF(data_rececao, data_encomenda))'])
            ->from('encomendas')
            ->where(['estado' => 'Recebida'])
            ->scalar();
        $mediaEntrega = $mediaEntrega ? round($mediaEntrega, 1) : 0;

        // Materiais com mais saída de stock (top 5)
        $topSaidas = (new \yii\db\Query())
            ->select(['m.nome_material', 'SUM(mov.quantidade) AS total_saida'])
            ->from(['mov' => 'movimentacoes'])
            ->innerJoin(['m' => 'materiais'], 'mov.material_id = m.id')
            ->where(['mov.tipo' => \common\models\Movimento::TIPO_SAIDA])
            ->groupBy('mov.material_id')
            ->orderBy(['total_saida' => SORT_DESC])
            ->limit(5)
            ->all();

        $topSaidasProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $topSaidas,
            'pagination' => false,
        ]);

        // Últimos movimentos (top 10)
        $ultimosMovimentos = \common\models\Movimento::find()
            ->orderBy(['data_movimentacao' => SORT_DESC])
            ->limit(10)
            ->all();

        $ultimosMovimentosProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $ultimosMovimentos,
            'pagination' => false,
        ]);

        return $this->render('index', [
            'valorTotalStock' => $valorTotalStock,
            'materiaisSemStock' => $materiaisSemStock,
            'materiaisAbaixoMinimo' => $materiaisAbaixoMinimo,
            'encomendasPendentes' => $encomendasPendentes,
            'mediaEntrega' => $mediaEntrega,
            'topSaidasProvider' => $topSaidasProvider,
            'ultimosMovimentosProvider' => $ultimosMovimentosProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Verifica se é operador após login
            if (Yii::$app->user->identity->cargo === 'operador') {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'Operadores não têm acesso ao painel de administração.');
                return $this->redirect(['access-denied']);
            }
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Access Denied action.
     *
     * @return string
     */
    public function actionAccessDenied()
    {
        $this->layout = 'blank';
        
        // Se ainda estiver logado, faz logout
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }
        
        return $this->render('access-denied');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
