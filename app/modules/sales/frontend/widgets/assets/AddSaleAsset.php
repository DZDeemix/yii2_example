<?php
namespace modules\sales\frontend\widgets\assets;

use frontend\assets\AppAsset;
use vova07\select2\Select2Asset;
use yii\jui\JuiAsset;
use yii\web\AssetBundle;
use yz\icons\FontAwesomeAsset;

class AddSaleAsset extends AssetBundle
{
    public $sourcePath = '@modules/sales/frontend/widgets/assets/add-sale';

    public $js = [
        'js/add_sale.js',
    ];

    public $css = [
        'css/add_sale.css',
    ];

    public $depends = [
        AppAsset::class,
        JuiAsset::class,
        FontAwesomeAsset::class,
        Select2Asset::class,
    ];

    public function init()
    {
        $isDev = ($_ENV['YII_ENV'] ?? null) == 'dev';
        $this->publishOptions['forceCopy'] = $isDev;

        parent::init();
    }
}