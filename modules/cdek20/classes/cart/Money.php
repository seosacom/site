<?php


namespace Seleda\Cdek\Cart;


class Money
{
    private $value;

    public function setValue($val)
    {
        $this->value = $val;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
}