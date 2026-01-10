<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Base controller for backend - sem bloqueio de operador aqui
 */
class BaseController extends Controller
{
    // Remover toda a lógica de bloqueio de operador
    // O bloqueio é feito apenas no SiteController::actionLogin
}
