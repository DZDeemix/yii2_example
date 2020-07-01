<?php

namespace modules\profiles\frontend;
use modules\profiles\frontend\api\v1\ApiV1;


/**
 * Class Module
 */
class Module extends \modules\profiles\common\Module
{
    public function init()
    {
        parent::init();

        $this->modules = [
            'api-v1' => [
                'class' => ApiV1::class,
            ]
        ];
    }

}