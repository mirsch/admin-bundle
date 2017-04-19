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

namespace Mirsch\Bundle\AdminBundle\Model;

use Serializable;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

interface AdminUserInterface extends AdvancedUserInterface, EquatableInterface, Serializable
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username);

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setIsActive($active);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @param string $locale
     *
     * @return $this
     */
    public function setLocale($locale);

    /**
     * Returns the roles granted to the user, roles of groups are NOT included
     *
     * @return string[]
     */
    public function getUserRoles();

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setUserRoles(array $roles);

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getGroups();

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $groups
     *
     * @return $this
     */
    public function setGroups($groups);

    /**
     * Returns the roles granted to the user, roles of groups are included
     *
     * <code>
     * public function getRoles()
     * {
     *     return ['ROLE_USER'];
     * }
     * </code>
     *
     * @return string[] The user roles
     */
    public function getRoles();

}
