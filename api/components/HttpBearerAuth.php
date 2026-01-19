<?php

namespace api\components;

use yii\filters\auth\HttpBearerAuth as YiiHttpBearerAuth;

class HttpBearerAuth extends YiiHttpBearerAuth
{
    // Pode customizar se necessário, mas o padrão já funciona para tokens simples
}
