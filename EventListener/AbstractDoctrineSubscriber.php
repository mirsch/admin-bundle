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

namespace Mirsch\Bundle\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\RuntimeReflectionService;
use Doctrine\ORM\Events;
use InvalidArgumentException;
use Mirsch\Bundle\AdminBundle\Model\MappedSuperClassToEntityInterface;

/**
 * The main principle of resources and most of this code is stripped out of the Sylius ResourceBundle
 * (c) Paweł Jędrzejewski https://github.com/Sylius
 * MIT License
 */
abstract class AbstractDoctrineSubscriber implements EventSubscriber
{

    /**
     * @var array
     */
    protected $resources;

    /**
     * @var \Doctrine\Common\Persistence\Mapping\RuntimeReflectionService
     */
    private $reflectionService;

    /**
     * AbstractDoctrineSubscriber constructor.
     *
     * @param array $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata
     *
     * @return bool
     */
    protected function isResource(ClassMetadata $metadata)
    {
        $reflectionClass = $metadata->getReflectionClass();
        if (!$reflectionClass) {
            return false;
        }

        return $reflectionClass->implementsInterface(MappedSuperClassToEntityInterface::class);
    }

    /**
     * @param string $class
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function resourceByClass($class)
    {
        foreach ($this->resources as $alias => $config) {
            if ($config['entity'] == $class) {
                return $config;
            }
        }

        throw new InvalidArgumentException(sprintf('Resource with model class "%s" does not exist.', $class));
    }

    /**
     * @return \Doctrine\Common\Persistence\Mapping\RuntimeReflectionService
     */
    protected function getReflectionService()
    {
        if ($this->reflectionService === null) {
            $this->reflectionService = new RuntimeReflectionService();
        }

        return $this->reflectionService;
    }

}
