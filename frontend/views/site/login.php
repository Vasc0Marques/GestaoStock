<?php
use yii\bootstrap\Html;
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.login-main {
    display: flex;
    height: 88.35vh;
    min-height: 500px;
    background: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 2px 16px #0001;
    overflow: hidden;
}
.login-users {
    flex: 7;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    gap: 32px 24px;
    padding: 40px 32px;
    background: #fff;
}
.user-card {
    width: 180px;
    height: 180px;
    background: #34495e;
    color: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px #0002;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    padding-bottom: 24px;
    cursor: pointer;
    border: 3px solid transparent;
    transition: border 0.2s, box-shadow 0.2s;
}
.user-card.selected {
    border: 3px solid #1abc9c;
    box-shadow: 0 4px 16px #1abc9c33;
}
.user-card .user-name {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 4px;
}
.user-card .user-role {
    font-size: 1rem;
    opacity: 0.8;
}
.login-pin {
    flex: 3;
    background: #2c3e50;
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
}
.pin-title {
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 24px;
}
.pin-input {
    font-size: 2rem;
    letter-spacing: 16px;
    background: #fff;
    color: #222;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    width: 180px;
    text-align: center;
    margin-bottom: 24px;
}
.pin-keyboard {
    display: grid;
    grid-template-columns: repeat(3, 60px);
    gap: 12px;
}
.pin-key {
    width: 60px;
    height: 60px;
    background: #34495e;
    color: #fff;
    font-size: 1.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s;
}
.pin-key:hover {
    background: #1abc9c;
}
</style>
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
    // Não é necessário JS para seleção do user, pois é feito por URL
    // PIN keyboard logic
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
    // Submit on Enter
    pinInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('pin-login-form').submit();
        }
    });
</script>