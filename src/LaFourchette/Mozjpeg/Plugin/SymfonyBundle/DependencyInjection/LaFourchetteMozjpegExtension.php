<?php

namespace LaFourchette\Mozjpeg\Plugin\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Reference;

class LaFourchetteMozjpegExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $bin => $binConfig) {
            foreach ($binConfig as $name => $value) {
                $container->setParameter(sprintf('la_fourchette_mozjpeg.%s.%s', $bin, $name), $value);
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
