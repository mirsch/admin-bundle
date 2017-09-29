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

namespace Mirsch\Bundle\AdminBundle\Tests\EventListener;

use Mirsch\Bundle\AdminBundle\Entity\AdminUser;
use Mirsch\Bundle\AdminBundle\EventListener\UserLocaleListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserLocaleListenerTest extends TestCase
{

    /**
     * @param \Mirsch\Bundle\AdminBundle\Model\AdminUserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession($user)
    {
        $session = new Session(new MockArraySessionStorage());
        $listener = new UserLocaleListener($session);

        $token = new UsernamePasswordToken($user, null, 'main', []);
        $session->set('_security_main', serialize($token));
        $session->save();

        $event = new InteractiveLoginEvent(new Request(), $token);
        $listener->onInteractiveLogin($event);

        return $session;
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginWithoutUserLocale()
    {
        $session = $this->getSession(new AdminUser());
        $this->assertEquals('', $session->get('_locale'));
    }

    /**
     * @return void
     */
    public function testOnInteractiveLoginWithUserLocale()
    {
        $user = new AdminUser();
        $user->setLocale('de');

        $session = $this->getSession($user);
        $this->assertEquals('de', $session->get('_locale'));
    }

}
