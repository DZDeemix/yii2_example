<?php

namespace modules\actions\backend;

use yz\icons\Icons;

class Module extends \modules\actions\common\Module
{
    public function getAdminMenu()
    {
        return [
            [

                'label' => 'Акции',
                'icon' => Icons::o('dollar'),
                'items' => [
                    [
                        'route' => ['/actions/action/index'],
                        'label' => 'Список акций',
                        'icon' => Icons::o('tasks'),
                    ],
                    [
                        'route' => ['/actions/action-participant/index'],
                        'label' => 'Участники акций',
                        'icon' => Icons::o('list-ul'),
                    ],
                    [
                        'route' => ['/actions/action-type/index'],
                        'label' => 'Типы акций',
                        'icon' => Icons::o('list-ul'),
                    ],

                ]
            ]
        ];
    }

}