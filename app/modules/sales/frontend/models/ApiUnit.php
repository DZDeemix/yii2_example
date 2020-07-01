<?php

namespace modules\sales\frontend\models;

use modules\sales\common\models\Group;
use modules\sales\common\models\Unit;

class ApiUnit extends Unit
{
    public function fields()
    {
        return [
            'id',
            'name',
            'short_name',
        ];
    }
}