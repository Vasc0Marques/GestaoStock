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
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 350px;
            background: #2c3e50;
            display: flex;
            flex-direction: column;
            padding: 20px 10px;
            gap: 15px;
            flex-shrink: 0;
        }

        .sidebar-btn {
            width: 100%;
            height: 360px;
            padding: 15px 10px;
            border: none;
            background: #34495e;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 30px;
        }

        .sidebar-btn:hover {
            background: #1abc9c;
            color: #fff;
            text-decoration: none;
        }

        .main-content-wrapper {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .content-main {
            flex: 1;
            overflow-y: auto;
        }
    </style>
    <?php $this->head() ?>
</head>

<body style="height: 100vh; margin: 0; padding: 0; display: flex; flex-direction: column;">
    <?php $this->beginBody() ?>

    <header style="height: 60px; display: flex; align-items: center; justify-content: center; background: #34495e; color: #fff; position: relative; margin: 0; padding: 0 30px;">
        <a href="/gestaostock/frontend/web/site/index" style="margin: 0; font-size: 1.8rem; font-weight: bold; color: #fff; text-decoration: none;">
            Gestão Stock
        </a>
        <div style="position: absolute; right: 30px;">
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('<i class="fa fa-sign-in"></i> Login', ['/site/login'], ['class' => 'btn btn-link', 'style' => 'color: #fff; text-decoration: none; font-weight: 500;']) ?>
            <?php else: ?>
                <?= Html::beginForm(['/site/logout'], 'post', ['style' => 'display: inline;']) ?>
                <span style="color: #fff; margin-right: 15px;"><i class="fa fa-user"></i> <?= Html::encode(Yii::$app->user->identity->username) ?></span>
                <?= Html::submitButton('<i class="fa fa-sign-out"></i> Logout', ['class' => 'btn btn-link', 'style' => 'color: #fff; text-decoration: none; font-weight: 500;']) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>
    </header>

    <div class="main-content-wrapper">
        <?php if (!(Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'login')): ?>
            <?= Sidebar::widget() ?>
        <?php endif; ?>
        <main role="main" class="content-main" style="margin: 0; padding: 0;">
            <div class="container" style="margin: 0; padding: 0; max-width: 100%; width: 100%; height: 100%;">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </main>
    </div>

    <footer style="height: 50px; display: flex; align-items: center; justify-content: center; background: #34495e; color: #fff; margin: 0; padding: 0;">
        <p style="margin: 0; font-size: 0.9rem;">&copy; Gestão Stock <?= date('Y') ?></p>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
