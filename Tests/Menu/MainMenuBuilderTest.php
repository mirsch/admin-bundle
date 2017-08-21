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

namespace Mirsch\Bundle\AdminBundle\Tests\Menu;

use Knp\Menu\MenuFactory;
use Mirsch\Bundle\AdminBundle\Menu\MainMenuBuilder;
use PHPUnit_Framework_TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuBuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * test if menu is build and users/groups menu is not visible
     *
     * @return void
     */
    public function testCreateMenuWithoutPermissions()
    {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(false);
        $menuBuilder = new MainMenuBuilder(new MenuFactory(), new EventDispatcher(), $authChecker);
        $menu = $menuBuilder->createMenu();

        $this->assertArrayHasKey('dashboard', $menu->getChildren(), 'Dashboard Menu exists');
        $this->assertArrayNotHasKey('users_groups', $menu->getChildren(), 'Users/Groups Menu does not exist');
    }

    /**
     * test if menu is build and users/groups menu is visible
     *
     * @return void
     */
    public function testCreateMenuWithPermissions()
    {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authChecker->method('isGranted')->willReturn(true);
        $menuBuilder = new MainMenuBuilder(new MenuFactory(), new EventDispatcher(), $authChecker);
        $menu = $menuBuilder->createMenu();

        $this->assertArrayHasKey('dashboard', $menu->getChildren(), 'Dashboard Menu exists');
        $this->assertArrayHasKey('users_groups', $menu->getChildren(), 'Users/Groups Menu does not exist');
    }

}
