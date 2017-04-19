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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class AdminGroupController extends AbstractController
{

    /**
     * @Route("/admin-group", name="admin_group")
     *
     * @Security("has_role('ROLE_ADMIN_USER_LIST')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $dataTable = $this->get('mirsch.admin.datatable.admingroup');
        $dataTable->buildDatatable();

        return $this->render('@MirschAdmin/generic/index.html.twig', [
            'page_title' => 'page.admin_group',
            'datatable' => $dataTable,
        ]);
    }

    /**
     * @Route("/admin-group/results", name="admin_group_results")
     *
     * @Security("has_role('ROLE_ADMIN_USER_LIST')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction()
    {
        $dataTable = $this->get('mirsch.admin.datatable.admingroup');
        $dataTable->buildDatatable();

        $query = $this->get('sg_datatables.query')->getQueryFrom($dataTable);

        return $query->getResponse();
    }

    /**
     * @Route("/admin-group/edit/{id}",
     *     name="admin_group_edit",
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
        /** @var \Mirsch\Bundle\AdminBundle\Model\AdminGroupInterface $group */
        $group = $this->findOr404('mirsch.admin.model.admin_group.entity', $request->get('id'));

        $form = $this->createForm($this->getParameter('mirsch.admin.model.admin_group.form'), $group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $group = $form->getData();
            if ($this->persistAndFlush($group)) {
                $this->addSuccessMessage(
                    $this->get('translator')->trans('mirsch.admin.success.saved')
                );
            }

            return $this->redirectToRoute('admin_group_edit', ['id' => $group->getId()]);
        }

        return $this->render('@MirschAdmin/generic/edit.html.twig', [
            'page_title' => 'page.admin_group',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin-group/new", name="admin_group_new")
     *
     * @Security("has_role('ROLE_ADMIN_USER_EDIT')")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $group = $this->newEntityFromParam('mirsch.admin.model.admin_group.entity');
        $form = $this->createForm($this->getParameter('mirsch.admin.model.admin_group.form'), $group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $group = $form->getData();
            if (!$this->persistAndFlush($group)) {
                return $this->redirectToRoute('admin_group_new');
            }

            $this->addSuccessMessage(
                $this->get('translator')->trans('mirsch.admin.success.saved')
            );

            return $this->redirectToRoute('admin_group_edit', ['id' => $group->getId()]);
        }

        return $this->render('@MirschAdmin/generic/edit.html.twig', [
            'page_title' => 'page.admin_group',
            'form' => $form->createView(),
        ]);
    }

}
