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

namespace Mirsch\Bundle\AdminBundle\Tests\DependencyInjection;

use InvalidArgumentException;
use Mirsch\Bundle\AdminBundle\DependencyInjection\MirschAdminExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class MirschAdminExtensionTest extends TestCase
{

    /**
     * test if configuration is loaded
     *
     * @return void
     */
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $loader = new MirschAdminExtension();
        $loader->load([[]], $container);
        $this->assertTrue($container->hasDefinition('mirsch.admin.event_listener.orm_mapped_super_class_subscriber'), 'ORM event listener mapped super class registered');
        $this->assertTrue($container->hasDefinition('mirsch.admin.event_listener.orm_repository_class_subscriber'), 'ORM event listener repository class registered');
        $this->assertTrue($container->hasDefinition('mirsch.admin.event_listener.role_hierarchy_listener'), 'Role hierarchy listener registered');
        $this->assertTrue($container->hasDefinition('mirsch.admin.event_listener.locale_listener'), 'Locale listener registered');
        $this->assertTrue($container->hasDefinition('mirsch.admin.event_listener.user_locale_listener'), 'User Locale listener registered');
        $this->assertTrue($container->hasDefinition('mirsch.admin.menu.main_menu_builder'), 'Main menu builder loaded');
        $this->assertTrue($container->hasDefinition('mirsch.admin.menu.main_menu'), 'Main menu registered');
        $this->assertTrue($container->hasDefinition('mirsch.admin.datatable.adminuser'), 'User Datatable loaded');
        $this->assertTrue($container->hasDefinition('mirsch.admin.datatable.admingroup'), 'User Datatable loaded');
        $this->assertTrue($container->hasDefinition('mirsch.admin.form.roles_type'), 'Roles Type loaded');
        $this->assertTrue($container->hasDefinition('mirsch.admin.form.admin_user_type'), 'User Type loaded');
        $this->assertTrue($container->hasDefinition('mirsch.admin.form.admin_group_type'), 'Group Type loaded');
    }

    /**
     * test if parameters are generated and doctrine resolve_target_entities are set
     *
     * @return void
     */
    public function testPrepend()
    {
        $container = new ContainerBuilder();
        $loader = new MirschAdminExtension();
        $config = Yaml::parse(file_get_contents(__DIR__ . '/../../Resources/config/resources.yml'));
        $container->prependExtensionConfig('mirsch_admin', $config['mirsch_admin']);
        $loader->load($config, $container);
        $loader->prepend($container);

        $this->assertTrue($container->hasParameter('mirsch.resources'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_user.entity'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_user.interface'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_user.repository'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_user.form'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_user.datatable'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_group.entity'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_group.interface'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_group.repository'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_group.form'));
        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_group.datatable'));

        $doctrineConfig = $container->getExtensionConfig('doctrine');
        $doctrineConfig = reset($doctrineConfig);

        $this->assertArrayHasKey('Mirsch\Bundle\AdminBundle\Model\AdminUserInterface', $doctrineConfig['orm']['resolve_target_entities']);
        $this->assertArrayHasKey('Mirsch\Bundle\AdminBundle\Model\AdminGroupInterface', $doctrineConfig['orm']['resolve_target_entities']);
        $this->assertEquals($doctrineConfig['orm']['resolve_target_entities']['Mirsch\Bundle\AdminBundle\Model\AdminUserInterface'], 'Mirsch\Bundle\AdminBundle\Entity\AdminUser');
        $this->assertEquals($doctrineConfig['orm']['resolve_target_entities']['Mirsch\Bundle\AdminBundle\Model\AdminGroupInterface'], 'Mirsch\Bundle\AdminBundle\Entity\AdminGroup');
    }

    /**
     * test if an exception is thrown if an entity is invalid
     *
     * @return void
     */
    public function testValidateResourceEntityException()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $container = new ContainerBuilder();
        $loader = new MirschAdminExtension();
        $config = Yaml::parse(file_get_contents(__DIR__ . '/../../Resources/config/resources.yml'));
        $config['mirsch_admin']['resources']['admin_user']['entity'] = '\StdClass';
        $container->prependExtensionConfig('mirsch_admin', $config['mirsch_admin']);
        $loader->load($config, $container);
        $loader->prepend($container);
    }

    /**
     * test resources with undefined interfaces
     *
     * @return void
     */
    public function testResourceWithoutInterface()
    {
        $container = new ContainerBuilder();
        $loader = new MirschAdminExtension();
        $config = Yaml::parse(file_get_contents(__DIR__ . '/../../Resources/config/resources.yml'));
        unset($config['mirsch_admin']['resources']['admin_user']['interface']);
        $container->prependExtensionConfig('mirsch_admin', $config['mirsch_admin']);
        $loader->load($config, $container);
        $loader->prepend($container);
        $doctrineConfig = $container->getExtensionConfig('doctrine');
        $doctrineConfig = reset($doctrineConfig);

        $this->assertTrue($container->hasParameter('mirsch.admin.model.admin_user.entity'));
        $this->assertEquals($container->getParameter('mirsch.admin.model.admin_user.entity'), 'Mirsch\Bundle\AdminBundle\Entity\AdminUser');
        $this->assertArrayNotHasKey('Mirsch\Bundle\AdminBundle\Model\AdminUserInterface', $doctrineConfig['orm']['resolve_target_entities']);
        $this->assertArrayHasKey('Mirsch\Bundle\AdminBundle\Model\AdminGroupInterface', $doctrineConfig['orm']['resolve_target_entities']);
    }

}
