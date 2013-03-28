<?php
/*
 * This file is a part of JamBundle. Please read the LICENSE and
 * README.md files for more informations about this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 * @link https://github.com/davidjegat/JamBundle
 */

namespace Djeg\JamBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * JamBundle extension
 * 
 * @author David jegat <david.jegat@gmail.com>
 */
class DjegJamExtension extends Extension
{

	/**
     * Configure the JamBundle
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new JamConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        // set the jam binary path
        $container->setParameter('djeg_jam.jam', $config['jam']);
        $container->setParameter('djeg_jam.uri', $config['uri']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

}