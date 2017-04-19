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

namespace Mirsch\Bundle\AdminBundle\Security;

use Mirsch\Bundle\AdminBundle\Event\RoleHierarchyEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy as SymfonyRoleHierarchy;

/**
 * builds role hierarchy tree like the original Symfony roles are stored in security.yml
 *
 * @package Mirsch\Bundle\AdminBundle\Security
 */
class RoleHierarchy extends SymfonyRoleHierarchy
{

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param array $hierarchy
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(array $hierarchy, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        parent::__construct($this->buildRolesArray($hierarchy));
    }

    /**
     * @param array $hierarchy
     *
     * @return array
     */
    private function buildRolesArray(array $hierarchy)
    {
        $event = new RoleHierarchyEvent($hierarchy);
        $this->eventDispatcher->dispatch(RoleHierarchyEvent::EVENT, $event);
        $roles = $event->getHierarchy();
        $hierarchy = array_merge_recursive($hierarchy, $roles);
        $hierarchy['ROLE_SUPER_ADMIN'] = array_unique($hierarchy['ROLE_SUPER_ADMIN']);

        return $hierarchy;
    }

}
