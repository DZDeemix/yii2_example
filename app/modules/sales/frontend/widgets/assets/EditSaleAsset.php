<?php
namespace modules\sales\frontend\widgets\assets;

use frontend\assets\AppAsset;
use vova07\select2\Select2Asset;
use yii\jui\JuiAsset;
use yii\web\AssetBundle;
use yii\web\View;
use yz\icons\FontAwesomeAsset;

class EditSaleAsset extends AssetBundle
{
    public $sourcePath = '@modules/sales/frontend/widgets/assets/edit-sale';

    public $js = [
        'js/edit-sale.js',
    ];

    public $css = [
        'css/edit-sale.css',
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

    public function registerAssetFiles($view)
    {
        $sale_id = \Yii::$app->request->get('id');
        $view->registerJs("var sale_id = '{$sale_id}';", View::POS_HEAD, __CLASS__);

        return parent::registerAssetFiles($view);
    }
}
