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

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * The main principle of resources and most of this code is stripped out of the Sylius ResourceBundle
 * (c) Paweł Jędrzejewski https://github.com/Sylius
 * MIT License
 */
final class OrmRepositoryClassSubscriber extends AbstractDoctrineSubscriber
{

    /**
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     *
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $this->setCustomRepositoryClass($metadata);
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     *
     * @return void
     */
    private function setCustomRepositoryClass(ClassMetadata $metadata)
    {
        try {
            $resourceMetadata = $this->resourceByClass($metadata->getName());
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        if (isset($resourceMetadata['repository']) && $resourceMetadata['repository']) {
            $metadata->setCustomRepositoryClass($resourceMetadata['repository']);
        }
    }

}
