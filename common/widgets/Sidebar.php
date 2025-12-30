<?php

namespace common\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Sidebar widget renders the main navigation sidebar
 */
class Sidebar extends Widget
{
    public $items = [];

    public function init()
    {
        parent::init();
        if (empty($this->items)) {
            $this->items = [
                ['label' => 'Consultar Stock', 'url' => ['/stock-terminal/consultar']],
                ['label' => 'Saida Stock', 'url' => ['/stock-terminal/saida']],
                ['label' => 'Consultar Encomendas', 'url' => ['/encomendas-terminal/consultar']],
            ];
        }
    }

    public function run()
    {
        $html = Html::beginTag('div', ['class' => 'sidebar']);

        $route = \Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id;

        foreach ($this->items as $item) {
            $url = is_array($item['url']) ? $item['url'][0] : $item['url'];
            // Extrai o controller/action do url
            $isActive = false;
            if (is_array($item['url'])) {
                $routeParts = explode('/', ltrim($url, '/'));
                if (isset($routeParts[1])) {
                    $itemRoute = $routeParts[0] . '/' . $routeParts[1];
                } else {
                    $itemRoute = $routeParts[0] . '/index';
                }
                $isActive = stripos($route, $itemRoute) === 0;
            }
            $btnClass = 'sidebar-btn' . ($isActive ? ' active' : '');
            $html .= Html::a(
                $item['label'],
                $item['url'],
                ['class' => $btnClass]
            );
        }

        $html .= Html::endTag('div');

        return $html;
    }
}
