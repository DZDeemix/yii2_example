<?php

namespace modules\profiles\backend;

use modules\profiles\backend\rbac\Rbac;
use yz\icons\Icons;

/**
 * Class Module
 */
class Module extends \modules\profiles\common\Module
{
    public function getName()
    {
        return 'Профили участников';
    }

    public function getAdminMenu()
    {
        return [
            [
                'label' => 'Участники программы',
                'icon' => Icons::o('user'),
                'items' => [
                    [
                        'route' => ['/profiles/profiles/index'],
                        'label' => 'Профили участников',
                        'icon' => Icons::o('group'),
                    ],
                    [
                        'route' => ['/profiles/leaders/index'],
                        'label' => 'Администраторы',
                        'icon' => Icons::o('group'),
                    ],

                    [
                        'route' => ['/profiles/import-profiles/index'],
                        'label' => 'Загрузка участников',
                        'icon' => Icons::o('upload'),
                    ],
                    [
                        'route' => ['/profiles/profile-transactions/index'],
                        'label' => 'Движение баллов участников',
                        'icon' => Icons::o('rub'),
                    ],
                    [
                        'route' => ['/profiles/import-dealers/index'],
                        'label' => 'Загрузка РТТ и дистрибьюторов',
                        'icon' => Icons::o('upload'),
                    ],
                    [
                        'route' => ['/profiles/dealers/index'],
                        'label' => 'РТТ и Дистрибьюторы',
                        'icon' => Icons::o('flag'),
                    ],
                ]
            ],
        ];
    }

    public function getAuthItems()
    {
        return array_merge(parent::getAuthItems(), Rbac::dependencies());
    }
}
