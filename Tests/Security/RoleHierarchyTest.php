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

namespace Mirsch\Bundle\AdminBundle\Tests\Security;

use Mirsch\Bundle\AdminBundle\EventListener\RoleHierarchyListener;
use Mirsch\Bundle\AdminBundle\Event\RoleHierarchyEvent;
use Mirsch\Bundle\AdminBundle\Security\RoleHierarchy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Role\Role;

/**
 * builds role hierarchy tree like the original Symfony roles are stored in security.yml
 *
 * @package Mirsch\Bundle\AdminBundle\Security
 */
class RoleHierarchyTest extends TestCase
{

    /**
     * test roles hierarchy via event listener
     *
     * @return void
     */
    public function testBuildRolesArray()
    {
        $dispatcher = new EventDispatcher();
        $listener = new RoleHierarchyListener();
        $dispatcher->addListener(RoleHierarchyEvent::EVENT, [$listener, 'onRoleHierarchyEvent']);

        $hierarchy = new RoleHierarchy(['ROLE_SUPER_ADMIN' => []], $dispatcher);
        $roles = $hierarchy->getReachableRoles([new Role('ROLE_SUPER_ADMIN')]);

        $this->assertEquals($roles[0]->getRole(), 'ROLE_SUPER_ADMIN');
        $this->assertEquals($roles[1]->getRole(), 'ROLE_ADMIN_USER_LIST');
        $this->assertEquals($roles[2]->getRole(), 'ROLE_ADMIN_USER_EDIT');
    }

}
