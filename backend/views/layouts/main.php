<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use dmstr\web\AdminLteAsset;

AdminLteAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body.modal-open {
            overflow: auto !important;
        }
        .modal-backdrop {
            display: none !important;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?= Yii::$app->homeUrl ?>" class="logo">
            <span class="logo-mini"><b>GS</b></span>
            <span class="logo-lg"><b>Gestão</b>Stock</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">

            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <li>
                            <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex align-items-center'])
                                . '<span style="margin-right:12px;"><i class="fa fa-user"></i> ' . Html::encode(Yii::$app->user->identity->username) . '</span>'
                                . Html::submitButton(
                                    '<i class="fa fa-sign-out"></i>',
                                    ['class' => 'btn btn-link logout navbar-btn', 'style' => 'padding-left:0; color:#222; font-weight:500; ']
                                )
                                . Html::endForm()
                            ?>
                        </li>
                    <?php else: ?>
                        <li><?= Html::a('<i class="fa fa-sign-in"></i> Login', ['/site/login'], ['class'=>'btn btn-link navbar-btn']) ?></li>
                    <?php endif; ?>
                </ul>
            </div>

        </nav>
    </header>

    <!-- Left side column -->
    <aside class="main-sidebar">
        <section class="sidebar">

            <!-- Sidebar user panel -->
            <?php if (!Yii::$app->user->isGuest): ?>
            <?php endif; ?>

            <!-- Compact icon-first Sidebar Menu -->
            <style>
                /* compact spacing and larger icon area similar to the provided design */
                .sidebar-menu .menu-item { padding: 10px 14px; clear: both; }
                .sidebar-menu .menu-item i { width: 34px; display: inline-block; text-align: center; font-size: 18px; margin-right: 10px; }
                .sidebar-menu .menu-item span { vertical-align: middle; }
                /* remove default bigger top padding for first item */
                .sidebar-menu { margin-top: 6px; }
            </style>

            <ul class="sidebar-menu">
                <li>
                    <a href="/gestaostock/backend/web/site/index" class="menu-item">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li><a href="/gestaostock/backend/web/material/index" class="menu-item"><i class="fa fa-cubes"></i> <span>Materiais</span></a></li>
                <li><a href="/gestaostock/backend/web/fornecedor/index" class="menu-item"><i class="fa fa-user"></i> <span>Fornecedores</span></a></li>
                <li><a href="/gestaostock/backend/web/encomenda/index" class="menu-item"><i class="fa fa-shopping-cart"></i> <span>Encomendas</span></a></li>
                <li><a href="/gestaostock/backend/web/movimento/index" class="menu-item"><i class="fa fa-list-alt"></i> <span>Movimentações</span></a></li>
            </ul>

            <?php
            echo dmstr\widgets\Menu::widget([
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    [
                        'label' => 'Configurações',
                        'icon' => 'folder',
                        'items' => [
                            [
                                'label' => 'Categorias',
                                'icon' => 'tags',
                                'url' => ['/categoria/index'],
                            ],
                            [
                                'label' => 'Zonas',
                                'icon' => 'map-marker',
                                'url' => ['/zona/index'],
                            ],
                            [
                                'label' => 'Utilizadores',
                                'icon' => 'users',
                                'url' => ['/user/index'],
                            ],
                        ],
                    ],
                ],
            ]);
            ?>
        </section>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">

        <section class="content-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>
        </section>

        <section class="content">
            <?= $content ?>
        </section>

    </div>

    <!-- Main Footer
    <footer class="main-footer">
        <strong>&copy; <?= date('Y') ?> GestãoStock</strong>
    </footer> -->

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
