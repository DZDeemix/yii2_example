<?php

namespace modules\sales\frontend\models;

use modules\sales\common\models\Group;

class ApiGroup extends Group
{
    public function fields()
    {
        return [
            'id',
            'name',
        ];
    }
}