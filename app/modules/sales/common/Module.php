<?php

namespace modules\sales\common;


/**
 * Class Module
 */
class Module extends \yz\Module
{
    /**
     * Is new sales creation allowed or not
     * @var bool
     */
    public $allowNewSalesCreation = true;

    /**
     * If documents are not on localhost
     * @var string
     */
    public $documentsBaseUrl = null;
}