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
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        // set the jam binary path
        $container->setParameter('djeg_jam.jam', $config['jam']);
        $container->setParameter('djeg_jam.base_url', $config['base_url']);
        $container->setParameter('djeg_jam.compiled_output', $config['compiled_output']);
        $container->setParameter('djeg_jam.rjs', $config['rjs']);
        $container->setParameter('djeg_jam.node', $config['node']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * Get the configuration
     * 
     * @return JamConfiguration
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        $configuration = new JamConfiguration();
        return $configuration;
    }

}