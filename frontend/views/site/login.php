<?php
use yii\bootstrap\Html;
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger" style="margin: 24px; font-size: 1.2em;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>
<div class="login-main">
    <div class="login-users">
        <?php
        $users = \common\models\User::find()->where(['status' => \common\models\User::STATUS_ACTIVE])->all();
        $selectedUserId = Yii::$app->request->get('id');
        foreach ($users as $user): ?>
            <a href="<?= \yii\helpers\Url::to(['site/login', 'id' => $user->id]) ?>" class="user-card<?= $selectedUserId == $user->id ? ' selected' : '' ?>">
                <div class="user-name"><?= Html::encode($user->username) ?></div>
                <div class="user-role"><?= Html::encode($user->email) ?></div>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="login-pin">
        <?php
        $selectedUser = null;
        if ($selectedUserId) {
            $selectedUser = \common\models\User::findOne(['id' => $selectedUserId, 'status' => \common\models\User::STATUS_ACTIVE]);
        }
        ?>
        <?php if ($selectedUser): ?>
            <div class="pin-title">PIN para <?= Html::encode($selectedUser->username) ?></div>
            <form id="pin-login-form" method="post" action="<?= \yii\helpers\Url::to(['site/login', 'id' => $selectedUser->id]) ?>">
                <input type="hidden" name="_csrf-frontend" value="<?= Yii::$app->request->getCsrfToken() ?>">
                <input type="hidden" name="user_id" value="<?= $selectedUser->id ?>">
                <input class="pin-input" name="pin" type="password" maxlength="4" autocomplete="off" value="" placeholder="----" readonly />
                <div class="pin-keyboard">
                    <?php for ($n = 1; $n <= 9; $n++): ?>
                        <button class="pin-key" type="button" data-key="<?= $n ?>"><?= $n ?></button>
                    <?php endfor; ?>
                    <div></div>
                    <button class="pin-key" type="button" data-key="0">0</button>
                    <button class="pin-key" type="button" data-key="del">⌫</button>
                </div>
                <div style="margin-top:24px;">
                    <button type="submit" class="btn btn-success btn-block" style="width:180px;">Entrar</button>
                </div>
            </form>
        <?php else: ?>
            <div class="pin-title">Selecione um utilizador para login</div>
        <?php endif; ?>
    </div>
</div>
<script>
    const pinInput = document.querySelector('.pin-input');
    document.querySelectorAll('.pin-key').forEach(function(btn) {
        btn.addEventListener('click', function() {
            let key = btn.getAttribute('data-key');
            if (key === 'del') {
                pinInput.value = pinInput.value.slice(0, -1);
            } else if (pinInput.value.length < 4) {
                pinInput.value += key;
            }
        });
    });
    pinInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('pin-login-form').submit();
        }
    });
</script>