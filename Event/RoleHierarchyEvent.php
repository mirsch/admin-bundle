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

namespace Mirsch\Bundle\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class RoleHierarchyEvent extends Event
{

    /**
     * name of the event
     */
    const EVENT = 'mirsch.admin.role_hierarchy';

    /**
     * @var array
     */
    protected $hierarchy;

    /**
     * RoleHierarchyEvent constructor.
     *
     * @param array $hierarchy
     */
    public function __construct(array $hierarchy)
    {
        $this->hierarchy = $hierarchy;
    }

    /**
     * @param array $rolesToAdd
     *
     * @return void
     */
    public function addToHierarchy(array $rolesToAdd)
    {
        $roles = [];
        $roles['ROLE_SUPER_ADMIN'] = $rolesToAdd;
        $this->hierarchy = array_merge_recursive($this->hierarchy, $roles);
    }

    /**
     * @return array
     */
    public function getHierarchy()
    {
        return $this->hierarchy;
    }

}
