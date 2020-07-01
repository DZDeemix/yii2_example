<?php
namespace modules\profiles\common\widgets\assets;

use ms\loyalty\taxes\common\assets\AccountProfileEditAsset;
use yii\web\AssetBundle;

class ProfileTaxAsset extends AssetBundle
{
    public $sourcePath = '@modules/profiles/common/widgets/assets/profile-tax';

    public $js = [
        'js/profile-tax.js',
    ];

    public $css = [
        'css/profile-tax.css',
    ];

    public $depends = [
        AccountProfileEditAsset::class,
    ];
}