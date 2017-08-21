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

namespace Mirsch\Bundle\AdminBundle\Tests\Kernel;

use Mirsch\Bundle\AdminBundle\Kernel\AdminKernel;
use Mirsch\Bundle\AdminBundle\MirschAdminBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\DebugBundle\DebugBundle;

class AdminKernelTest extends TestCase
{

    /**
     * @return void
     */
    public function testRegisterBundles()
    {
        $kernel = new AdminKernel('prod', false);
        $bundles = $kernel->registerBundles();

        $this->assertTrue(in_array(new MirschAdminBundle(), $bundles));
        $this->assertFalse(in_array(new DebugBundle(), $bundles));
    }

    /**
     * @return void
     */
    public function testRegisterBundlesDev()
    {
        $kernel = new AdminKernel('dev', false);
        $bundles = $kernel->registerBundles();

        $this->assertTrue(in_array(new MirschAdminBundle(), $bundles));
        $this->assertTrue(in_array(new DebugBundle(), $bundles));
    }

    /**
     * @return void
     */
    public function testGetCacheDir()
    {
        $kernel = new AdminKernel('dev', false);
        $dir = $kernel->getCacheDir();
        $this->assertStringEndsWith('var/cache/dev', $dir);
    }

    /**
     * @return void
     */
    public function testGetLogDir()
    {
        $kernel = new AdminKernel('dev', false);
        $dir = $kernel->getLogDir();
        $this->assertStringEndsWith('var/logs', $dir);
    }

}
