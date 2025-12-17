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

        foreach ($this->items as $item) {
            $html .= Html::a(
                $item['label'],
                $item['url'],
                ['class' => 'sidebar-btn']
            );
        }

        $html .= Html::endTag('div');

        return $html;
    }
}
