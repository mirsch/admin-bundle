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

interface AdminGroupInterface
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
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Returns the roles granted to the user.
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

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles);

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers();

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $users
     *
     * @return $this
     */
    public function setUsers($users);

}
