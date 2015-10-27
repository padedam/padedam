<?php

namespace NFQ\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NFQUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
