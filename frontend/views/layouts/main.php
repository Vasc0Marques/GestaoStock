<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use common\widgets\Sidebar;
use frontend\assets\AppAsset;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

AppAsset::register($this);
$this->registerCssFile('@web/css/login.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerCssFile('@web/css/stock-terminal.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerCssFile('@web/css/badges.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerCssFile('@web/css/sidebar.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerCssFile('@web/css/main.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="app-body">
    <?php $this->beginBody() ?>

    <?php if (!(Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'login')): ?>
        <header class="app-header">
            <a href="/gestaostock/frontend/web/site/index" class="app-logo">
                <i class="fa fa-cube"></i> Gestão Stock
            </a>
            <div class="app-user-info">
                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('<i class="fa fa-sign-in"></i> Login', ['/site/login'], ['class' => 'btn-link']) ?>
                <?php else: ?>
                    <span class="user-name">
                        <i class="fa fa-user"></i> <?= Html::encode(Yii::$app->user->identity->getNomeCompleto()) ?>
                        <?php if (Yii::$app->user->identity->cargo): ?>
                            <span class="user-role">(<?= Html::encode(ucfirst(Yii::$app->user->identity->cargo)) ?>)</span>
                        <?php endif; ?>
                    </span>
                    <?= Html::beginForm(['/site/logout'], 'post', ['style' => 'display: inline;'])
                        . Html::submitButton(
                            '<i class="fa fa-sign-out"></i> Logout',
                            ['class' => 'btn-logout']
                        )
                        . Html::endForm()
                    ?>
                <?php endif; ?>
            </div>
        </header>
    <?php endif; ?>

    <div class="main-content-wrapper">
        <?php if (!(Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'login')): ?>
            <?= Sidebar::widget() ?>
        <?php endif; ?>
        <main role="main" class="content-main">
            <div class="app-container">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </main>
    </div>

    <?php if (!(Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'login')): ?>
        <footer class="app-footer">
            <p>&copy; Gestão Stock <?= date('Y') ?></p>
        </footer>
    <?php endif; ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage(); ?>
