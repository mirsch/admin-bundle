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

use Mirsch\Bundle\AdminBundle\EventListener\LocaleListener;
use Mirsch\Bundle\AdminBundle\Kernel\AdminKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListenerTest extends TestCase
{

    /**
     * @param bool $locale
     * @param bool $previousSession
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest($locale = false, $previousSession = false)
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        if ($previousSession !== false) {
            $request->cookies->set($session->getName(), $previousSession); // trick $request->hasPreviousSession()
        }
        if ($locale !== false) {
            $request->attributes->set('_locale', $locale);
        }
        $request->setSession($session);

        $event = new GetResponseEvent(new AdminKernel('test', false), $request, HttpKernelInterface::MASTER_REQUEST);
        $listener = new LocaleListener('en');
        $listener->onKernelRequest($event);

        return $request;
    }

    /**
     * @return void
     */
    public function testGetSubscribedEvents()
    {
        $listener = new LocaleListener();
        $this->assertArrayHasKey(KernelEvents::REQUEST, $listener::getSubscribedEvents());
    }

    /**
     * @return void
     */
    public function testOnKernelRequestWithoutLocale()
    {
        $request = $this->getRequest();

        $this->assertEquals('', $request->getSession()->get('_locale'));
    }

    /**
     * @return void
     */
    public function testOnKernelRequestWithoutLocaleButPreviousSession()
    {
        $request = $this->getRequest(false, true);

        $this->assertEquals('', $request->getSession()->get('_locale'));
    }

    /**
     * @return void
     */
    public function testOnKernelRequestWithLocale()
    {
        $request = $this->getRequest('de', true);

        $this->assertEquals('de', $request->getSession()->get('_locale'));
    }

    /**
     * @return void
     */
    public function testOnKernelRequestWithLocaleFailsWithoutPreviousSession()
    {
        $request = $this->getRequest('de', false);

        $this->assertEquals('', $request->getSession()->get('_locale'));
    }

}
