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

use Mirsch\Bundle\AdminBundle\Event\RoleHierarchyEvent;

class RoleHierarchyListener
{

    /**
     * @param \Mirsch\Bundle\AdminBundle\Event\RoleHierarchyEvent $event
     *
     * @return void
     */
    public function onRoleHierarchyEvent(RoleHierarchyEvent $event)
    {
        $event->addToHierarchy([
            'ROLE_ADMIN_USER_LIST',
            'ROLE_ADMIN_USER_EDIT',
        ]);
    }

}
