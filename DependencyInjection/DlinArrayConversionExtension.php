<?php
/**
 * Created by David Lin
 * Project: ArrayConversion
 * Email: davidforest@gmail.com
 * User: davidlin
 * Date: 27/06/13
 * Time: 3:31 PM
 *
 */

namespace Dlin\Bundle\ArrayConversionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DlinArrayConversionExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');


        // probably make this configurable...
        $cacheDirectory = '%kernel.cache_dir%/dlin/ArrayConversionBundle/metadata';
        $cacheDirectory = $container->getParameterBag()->resolveValue($cacheDirectory);
        if (!is_dir($cacheDirectory)) {
            mkdir($cacheDirectory, 0777, true);
        }

        // the cache directory should be the first argument of the cache service
        $container
                ->getDefinition('dlin.array_converter.metadata.cache')
                ->replaceArgument(0, $cacheDirectory)
        ;

    }
}

