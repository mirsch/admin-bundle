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

namespace Mirsch\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * original https://gist.github.com/Glideh/27edd82b6b953b0b431225de9796c697
 *
 * @package Mirsch\Bundle\AdminBundle\Form
 */
class RolesType extends AbstractType
{

    /**
     * @var array|\Symfony\Component\Security\Core\Role\RoleInterface[]
     */
    protected $reachableRoles;

    /**
     * @var array
     */
    protected $roleChoices;

    /**
     * @var string
     */
    private $transPrefix = 'security.roles.';

    /**
     * RolesType constructor.
     *
     * @param \Symfony\Component\Security\Core\Role\RoleHierarchy $roleHierarchy
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $authorizationChecker
     */
    public function __construct(RoleHierarchy $roleHierarchy, AuthorizationChecker $authorizationChecker)
    {
        $superAdminRole = 'ROLE_SUPER_ADMIN';
        $currentPermissions = [new Role($superAdminRole)];
        $this->reachableRoles = $roleHierarchy->getReachableRoles($currentPermissions);
        $this->roleChoices = $this->getRoleChoices();
        if (!$authorizationChecker->isGranted($superAdminRole)) {
            unset($this->roleChoices[$this->transPrefix . $superAdminRole]);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'security.role_labels',
            'choices' => $this->roleChoices,
            'choice_translation_domain' => true,
            'expanded' => true,
            'multiple' => true,
        ]);
    }

    /**
     * @return array
     */
    protected function getRoleChoices()
    {
        $roles = [];
        /** @var \Symfony\Component\Security\Core\Role\Role $role */
        foreach ($this->reachableRoles as $role) {
            $roles[$this->transPrefix . $role->getRole()] = $role->getRole();
        }

        return $roles;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

}
