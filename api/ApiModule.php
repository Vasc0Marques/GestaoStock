<?php

namespace api;

use yii\base\Module;

class ApiModule extends Module
{
    public $controllerNamespace = 'api\controllers';

    public function init()
    {
        parent::init();
        // custom initialization code
    }
}
