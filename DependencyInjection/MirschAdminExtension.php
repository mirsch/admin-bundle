<?php

/*
 * This file is part of the MirschAdmin package.
 *
 * (c) Mirko Schaal and Contributors of the project
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

namespace Mirsch\Bundle\AdminBundle\DependencyInjection;

use Mirsch\Bundle\AdminBundle\Model\MappedSuperClassToEntityInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MirschAdminExtension extends Extension implements PrependExtensionInterface
{

    /**
     * prepend doctrine resolve target entities
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $this->loadResources($config['resources'], $container);
        $doctrineConfig = [];
        foreach ($container->getParameter('mirsch.resources') as $alias => $config) {
            if (empty($config['interface'])) {
                continue;
            }
            $doctrineConfig['orm']['resolve_target_entities'][$config['interface']] = $config['entity'];
        }
        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param array $resources
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    protected function loadResources(array $resources, ContainerBuilder $container)
    {
        foreach ($resources as $alias => $resourceConfig) {
            $resources = $container->hasParameter('mirsch.resources') ? $container->getParameter('mirsch.resources') : [];
            $resources = array_merge($resources, [$alias => $resourceConfig]);
            $this->validateResourceEntity($resourceConfig['entity']);
            foreach ($resourceConfig as $key => $class) {
                $key = sprintf('mirsch.admin.model.%s.%s', $alias, $key);
                $container->setParameter($key, $class);
            }
            $container->setParameter('mirsch.resources', $resources);
        }
    }

    /**
     * @param string $class
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     *
     * @return void
     */
    protected function validateResourceEntity($class)
    {
        if (!in_array(MappedSuperClassToEntityInterface::class, class_implements($class), true)) {
            throw new InvalidArgumentException(sprintf(
                'Class "%s" must implement "%s" to be registered as a Resource.',
                $class,
                MappedSuperClassToEntityInterface::class
            ));
        }
    }

}
