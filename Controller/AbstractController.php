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

namespace Mirsch\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractController extends Controller
{

    /**
     * @param string $paramName
     *
     * @return object
     */
    protected function newEntityFromParam($paramName)
    {
        $class = $this->getParameter($paramName);
        return new $class;
    }

    /**
     * @param string $paramName
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function repositoryFromParam($paramName)
    {
        return $this->getDoctrine()->getRepository($this->getParameter($paramName));
    }

    /**
     * @param string $paramName
     * @param string $primaryValue
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return object
     */
    protected function findOr404($paramName, $primaryValue)
    {
        $entity = $this->repositoryFromParam($paramName)->find($primaryValue);

        if ($entity === null) {
            throw new NotFoundHttpException(sprintf($this->trans('mirsch.admin.exception.not_found'), $primaryValue));
        }

        return $entity;
    }

    /**
     * @param mixed $object
     *
     * @return boolean
     */
    protected function persistAndFlush($object)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
        } catch (\Exception $exception) {
            $this->addErrorMessage($exception->getMessage());

            return false;
        }

        return true;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function addErrorMessage($message)
    {
        $this->addFlash('error', $message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function addSuccessMessage($message)
    {
        $this->addFlash('success', $message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function addInfoMessage($message)
    {
        $this->addFlash('info', $message);
    }

    /**
     * Translates the given message.
     *
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @return string The translated string
     */
    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param int $number The number to use to find the indice of the message
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @return string The translated string
     */
    protected function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->get('translator')->transChoice($id, $number, $parameters, $domain, $locale);
    }

}
