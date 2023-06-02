<?php


namespace Seleda\Cdek\Component\Cart\Package;


class PackageDecorator implements PackageDecoratorInterface
{
    private $package;
    
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    public function getPackage()
    {
        return $this->package;
    }
}