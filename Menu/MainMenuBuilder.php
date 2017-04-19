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

namespace Mirsch\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Mirsch\Bundle\AdminBundle\Event\MainMenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuBuilder
{

    /**
     * @var \Knp\Menu\FactoryInterface
     */
    protected $factory;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher,
        AuthorizationCheckerInterface $authorizationChecker
    ) {

        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenu()
    {
        $menu = $this->factory->createItem('root');

        $menu
            ->addChild('dashboard', ['route' => 'homepage'])
            ->setLabel('mirsch.admin.menu.main.dashboard')
            ->setLabelAttribute('icon', 'dashboard');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN_USER_LIST')) {
            $userMenu = $menu
                ->addChild('users_groups', ['route' => 'admin_user'])
                ->setLabel('mirsch.admin.menu.main.user')
                ->setLabelAttribute('icon', 'user-secret');
            $userMenu->addChild('users', ['route' => 'admin_user'])
                ->setLabel('mirsch.admin.menu.main.user')
                ->setLabelAttribute('icon', 'user');
            $userMenu->addChild('groups', ['route' => 'admin_group'])
                ->setLabel('mirsch.admin.menu.main.groups')
                ->setLabelAttribute('icon', 'users');
        }

        $this->eventDispatcher->dispatch(
            MainMenuBuilderEvent::EVENT,
            new MainMenuBuilderEvent(
                $this->factory,
                $menu,
                $this->authorizationChecker
            )
        );

        return $menu;
    }

}
