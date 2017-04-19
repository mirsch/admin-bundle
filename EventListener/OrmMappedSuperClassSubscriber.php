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

use Doctrine\ORM\Configuration;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use InvalidArgumentException;

/**
 * The main principle of resources and most of this code is stripped out of the Sylius ResourceBundle
 * (c) Paweł Jędrzejewski https://github.com/Sylius
 * MIT License
 */
class OrmMappedSuperClassSubscriber extends AbstractDoctrineSubscriber
{

    /**
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     *
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $this->convertToEntityIfNeeded($metadata);

        if (!$metadata->isMappedSuperclass) {
            $this->setAssociationMappings($metadata, $eventArgs->getEntityManager()->getConfiguration());
        } else {
            $this->unsetAssociationMappings($metadata);
        }
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata
     *
     * @return void
     */
    private function convertToEntityIfNeeded(ClassMetadataInfo $metadata)
    {
        if (!$metadata->isMappedSuperclass) {
            return;
        }

        if (!$this->isResource($metadata)) {
            return;
        }

        try {
            $this->resourceByClass($metadata->getName());
        } catch (InvalidArgumentException $exception) {
            return;
        }

        $metadata->isMappedSuperclass = false;
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata
     * @param \Doctrine\ORM\Configuration $configuration
     *
     * @return void
     */
    private function setAssociationMappings(ClassMetadataInfo $metadata, Configuration $configuration)
    {
        foreach (class_parents($metadata->getName()) as $parent) {
            if (false === in_array($parent, $configuration->getMetadataDriverImpl()->getAllClassNames())) {
                continue;
            }

            $parentMetadata = new ClassMetadata(
                $parent,
                $configuration->getNamingStrategy()
            );

            // Wakeup Reflection
            $parentMetadata->wakeupReflection($this->getReflectionService());

            // Load Metadata
            $configuration->getMetadataDriverImpl()->loadMetadataForClass($parent, $parentMetadata);

            if (!$this->isResource($parentMetadata)) {
                continue;
            }

            if ($parentMetadata->isMappedSuperclass) {
                foreach ($parentMetadata->getAssociationMappings() as $key => $value) {
                    if ($this->isRelation($value['type']) && !isset($metadata->associationMappings[$key])) {
                        $metadata->associationMappings[$key] = $value;
                    }
                }
            }
        }
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata
     *
     * @return void
     */
    private function unsetAssociationMappings(ClassMetadataInfo $metadata)
    {
        if (!$this->isResource($metadata)) {
            return;
        }

        foreach ($metadata->getAssociationMappings() as $key => $value) {
            if ($this->isRelation($value['type'])) {
                unset($metadata->associationMappings[$key]);
            }
        }
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isRelation($type)
    {
        return in_array(
            $type,
            [
                ClassMetadataInfo::MANY_TO_MANY,
                ClassMetadataInfo::ONE_TO_MANY,
                ClassMetadataInfo::ONE_TO_ONE,
            ],
            true
        );
    }

}
