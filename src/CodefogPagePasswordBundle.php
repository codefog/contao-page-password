<?php

declare(strict_types=1);

namespace Codefog\PagePasswordBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CodefogPagePasswordBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
