<?php


namespace modules\burnpoints\backend;


use yz\icons\Icons;

class Module extends \modules\burnpoints\common\Module
{
    public function getAdminMenu()
    {
        return [
            [
                'label' => $this->getName(),
                'icon' => Icons::o('fire-alt'),
                'items' => [
                    [
                        'route' => ['/burnpoints/settings/update'],
                        'label' => 'Настройки',
                        'icon' => Icons::o('cog'),
                    ],
                    [
                        'route' => ['/burnpoints/nullify/index'],
                        'label' => 'Обнуления',
                        'icon' => Icons::o('burn'),
                    ],
                ]
            ]
        ];
    }
}