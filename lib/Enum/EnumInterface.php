<?php

namespace Enum;

/**
 * Class EnumInterface
 * @package Enum
 */
interface EnumInterface
{
    /**
     * @param mixed $value
     */
    public function __construct($value = null);

    /**
     * @return mixed
     */
    public function getValue();
}
