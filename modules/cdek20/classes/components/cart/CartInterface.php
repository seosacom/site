<?php


namespace Seleda\Cdek\Component\Cart;


interface CartInterface
{
    public function getProducts();
    public function getTotalDimensions($cell);
}