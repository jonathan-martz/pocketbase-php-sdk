<?php

namespace Pb\Exception;

class FirstListItemNotFoundException extends \Exception
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
    }
}