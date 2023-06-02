<?php


namespace Seleda\Cdek\Component\Cart\Package;

use Seleda\Cdek\Component\Cart\Cart as ComponentCart;

abstract class PackageBuilder implements PackagesBuilderInterface
{
    protected $cart;

    public function __construct(ComponentCart $cart)
    {
        $this->cart = $cart;
    }
}