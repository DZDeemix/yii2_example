<?php

namespace modules\projects\backend;

use modules\projects\backend\rbac\Rbac;
use modules\projects\common\models\Project;
use yz\icons\Icons;

/**
 * Class Module
 */
class Module extends \modules\projects\common\Module
{
    public function getAdminMenu()
    {
        return [
            [
                'route' => ['/projects/projects/index'],
                'label' => 'Юрлица',
                'icon' => Icons::o('cubes'),
            ],
        ];
    }

    public function getAuthItems()
    {
        return array_merge(parent::getAuthItems(), Rbac::dependencies());
    }
}
