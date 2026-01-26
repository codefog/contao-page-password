<?php

declare(strict_types=1);

namespace Codefog\PagePasswordBundle\ContaoManager;

use Codefog\PagePasswordBundle\CodefogPagePasswordBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            (new BundleConfig(CodefogPagePasswordBundle::class))->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
