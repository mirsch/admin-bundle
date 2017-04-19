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

namespace Mirsch\Bundle\AdminBundle\Datatable;

use Doctrine\ORM\EntityManagerInterface;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractDatatable extends AbstractDatatableView
{

    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * AbstractDatatable constructor.
     *
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $securityToken
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param string $entityClassName
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        TranslatorInterface $translator,
        RouterInterface $router,
        EntityManagerInterface $em,
        $entityClassName
    ) {
        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em);
        $this->entityClassName = $entityClassName;

        $this->features->set([
            'highlight' => false,
            'highlight_color' => 'red',
        ]);

        $this->ajax->set([
            'url' => $this->getAjaxUrl(),
            'type' => 'GET',
            'pipeline' => 0,
        ]);

        $this->options->set([
            'class' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'row_id' => 'id',
        ]);
    }

    /**
     * @param array $topActions
     *
     * @return void
     */
    protected function addTopActions(array $topActions)
    {
        $this->topActions->set([
            'start_html' => '<div class="top-actions text-right">',
            'end_html' => '<hr /></div>',
            'actions' => $topActions,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return $this->entityClassName;
    }

    /**
     * @return string
     */
    abstract protected function getAjaxUrl();

}
