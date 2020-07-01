<?php

namespace modules\actions\common\types;

interface ActionTypeInterface
{
    /**
     * @return string
     */
    public function title();

    /**
     * @return string
     */
    public function shortDescription();

    /**
     * @return bool
     */
    public function validate();

    /**
     * @return integer
     */
    public function calculateBonuses();

    /**
     * @return bool
     */
    public function verify();

}