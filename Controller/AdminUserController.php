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

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AdminUserController extends AbstractController
{

    /**
     * @Route("/admin-user", name="admin_user")
     *
     * @Security("has_role('ROLE_ADMIN_USER_LIST')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $dataTable = $this->get('mirsch.admin.datatable.adminuser');
        $dataTable->buildDatatable();

        return $this->render('@MirschAdmin/generic/index.html.twig', [
            'page_title' => 'page.admin_user',
            'datatable' => $dataTable,
        ]);
    }

    /**
     * @Route("/admin-user/results", name="admin_user_results")
     *
     * @Security("has_role('ROLE_ADMIN_USER_LIST')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction()
    {
        $dataTable = $this->get('mirsch.admin.datatable.adminuser');
        $dataTable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($dataTable);

        return $query->getResponse();
    }

    /**
     * @Route("/admin-user/group-select", name="admin_user_group_select")
     *
     * @Security("has_role('ROLE_ADMIN_USER_LIST')")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function groupSelectAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException();
        }
        $q = $request->query->get('q');
        $page = $request->query->getInt('page') - 1 >= 0 ? $request->query->getInt('page') - 1 : 0;
        $limit = $request->query->getInt('page_limit');

        /** @var \Mirsch\Bundle\AdminBundle\Entity\AdminUserRepository $repository */
        $repository = $this->repositoryFromParam('mirsch.admin.model.admin_group.entity');

        $results = $repository
            ->createQueryBuilder('g')
            ->where('g.name LIKE :name')
            ->setParameter(':name', '%' . $q . '%')
            ->orderBy('g.name')
            ->setMaxResults($limit + 1)
            ->setFirstResult($page)
            ->getQuery()
            ->getResult();

        $more = count($results) > 10;
        $results = array_slice($results, 0, 10);

        $data = [];
        $data['results'] = [];
        foreach ($results as $result) {
            /** @var \Mirsch\Bundle\AdminBundle\Model\AdminGroupInterface $result */
            $data['results'][] = [
                'id' => $result->getId(),
                'text' => $result->getName(),
            ];
        }
        $data['pagination']['more'] = $more;

        return $this->json($data);
    }

    /**
     * @Route("/admin-users/edit/{id}",
     *     name="admin_user_edit",
     *     requirements={"id": "\d+"},
     *     options={"expose"=true}
     * )
     *
     * @Security("has_role('ROLE_ADMIN_USER_EDIT')")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        /** @var \Mirsch\Bundle\AdminBundle\Model\AdminUserInterface $user */
        $user = $this->findOr404('mirsch.admin.model.admin_user.entity', $request->get('id'));

        $oldPassword = $user->getPassword();

        $form = $this->createForm($this->getParameter('mirsch.admin.model.admin_user.form'), $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if ($user->getPassword() != '') {
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $newPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($newPassword);
            } else {
                $user->setPassword($oldPassword);
            }

            if ($this->persistAndFlush($user)) {
                $this->addSuccessMessage(
                    $this->get('translator')->trans('mirsch.admin.success.saved')
                );
            }

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        return $this->render('@MirschAdmin/generic/edit.html.twig', [
            'page_title' => 'page.admin_user',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-user/new", name="admin_user_new")
     *
     * @Security("has_role('ROLE_ADMIN_USER_EDIT')")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $user = $this->newEntityFromParam('mirsch.admin.model.admin_user.entity');
        $form = $this->createForm($this->getParameter('mirsch.admin.model.admin_user.form'), $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $newPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($newPassword);

            if (!$this->persistAndFlush($user)) {
                return $this->redirectToRoute('admin_user_new');
            }

            $this->addSuccessMessage(
                $this->get('translator')->trans('mirsch.admin.success.saved')
            );

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        return $this->render('@MirschAdmin/generic/edit.html.twig', [
            'page_title' => 'page.admin_user',
            'form' => $form->createView(),
        ]);
    }

}
