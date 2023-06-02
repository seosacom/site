<?php


namespace Seleda\Cdek\Component\Cart\Package;


class OnePackageAllOneBoxBuilder extends PackageBuilder
{
    // 5 Включены опции «Каждый товар на отдельной позиции» и «Все товары в одной коробке»
    public function build()
    {
        $packages = [];

        $package = new Package(1);
        $package_decorator = new PackageDecorator($package);

        foreach ($this->cart->getProducts() as $key => $product) {
            for ($i = 0; $i < $product->getAmount(); $i++) {
                $clone = clone $product;
                $clone->setAmountForce(1);
                $package_decorator = $clone->decor($package_decorator);
            }
        }

        $packages[] = $package_decorator->getPackage();

        return $packages;
    }
}