<?php
namespace modules\sales\frontend\widgets\assets;

/**
 * Created by PhpStorm.
 * User: MihailKri
 * Date: 20.04.2018
 * Time: 15:35
 */
use frontend\assets\AppAsset;
use yii\web\AssetBundle;

class SalesHistoryAsset extends AssetBundle
{
    public $sourcePath = '@modules/sales/frontend/widgets/assets/sales-history';

    public $js = [
        'js/sales_history.js',
    ];

    public $css = [
        'css/sales_history.css',
    ];

    public function init()
    {
        $isDev = ($_ENV['YII_ENV'] ?? null) == 'dev';
        $this->publishOptions['forceCopy'] = $isDev;

        parent::init();
    }
}