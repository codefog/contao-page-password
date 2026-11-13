<?php

declare(strict_types=1);

namespace Codefog\PagePasswordBundle\DependencyInjection;

use Codefog\PagePasswordBundle\EventListener\ChangeLanguageNavigationListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Terminal42\ChangeLanguage\PageFinder;

class CodefogPagePasswordExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        // The listener depends on terminal42/contao-changelanguage, which is optional
        if (!class_exists(PageFinder::class)) {
            $container->removeDefinition(ChangeLanguageNavigationListener::class);
        }
    }
}
