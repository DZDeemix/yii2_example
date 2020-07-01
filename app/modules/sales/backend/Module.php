<?php

namespace modules\sales\backend;

use modules\sales\backend\rbac\Rbac;
use modules\sales\common\app\models\ApiSale;
use modules\sales\common\app\SaleApplicationModule;
use modules\sales\common\models\Sale;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yz\icons\Icons;


/**
 * Class Module
 */
class Module extends \modules\sales\common\Module
{
    public function getName()
    {
        return 'Бонусы за продажу';
    }

    public function getAdminMenu()
    {
        return [
            [
                'label' => 'Продукция и продажи',
                'icon' => Icons::o('shopping-bag'),
                'items' => [
                    [
                        'route' => ['/sales/sales/index'],
                        'label' => 'Список продаж',
                        'icon' => Icons::o('shopping-bag'),
                    ],
                    [
                        'route' => ['/sales/sale-positions/index'],
                        'label' => 'Список позиций',
                        'icon' => Icons::o('shopping-basket'),
                    ],
                    [
                        'route' => ['/sales/products/index'],
                        'label' => 'Продукция',
                        'icon' => Icons::o('cubes'),
                    ],
//                    [
//                        'route' => ['/sales/categories/index'],
//                        'label' => 'Категории продукции',
//                        'icon' => Icons::o('cube'),
//                    ],
                    [
                        'route' => ['/sales/import-products/index'],
                        'label' => 'Импорт товаров',
                        'icon' => Icons::o('upload'),
                    ],
//                    [
//                        'route' => ['/sales/groups/index'],
//                        'label' => 'Подразделения',
//                        'icon' => Icons::o('bank'),
//                    ],
                    [
                        'route' => ['/sales/units/index'],
                        'label' => 'Единицы измерения',
                        'icon' => Icons::o('superscript'),
                    ],
                    [
                        'route' => ['/sales/sale-reports/index'],
                        'label' => 'Отчеты о продажах',
                        'icon' => Icons::o('list'),
                    ],
//                    [
//                        'route' => ['/sales/sale-validation-rules/index'],
//                        'label' => 'Правила проверки покупок',
//                        'icon' => Icons::o('check-square-o'),
//                    ],
                ]
            ],
        ];
    }

    public function getAuthItems()
    {
        return array_merge(parent::getAuthItems(), Rbac::dependencies());
    }
}
