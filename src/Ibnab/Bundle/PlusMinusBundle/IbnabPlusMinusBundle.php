<?php

namespace Ibnab\Bundle\PlusMinusBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class IbnabPlusMinusBundle extends Bundle
{
    public function getParent()
    {
        return 'OroProductBundle';
    }
}
