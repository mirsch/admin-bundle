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

class AdminGroupDatatable extends AbstractDatatable
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = [])
    {
        $this->addTopActions([
            [
                'route' => $this->router->generate('admin_group_new'),
                'label' => $this->translator->trans('datatables.actions.new'),
                'icon' => 'fa fa-plus',
                'attributes' => [
                    'rel' => 'tooltip',
                    'title' => $this->translator->trans('datatables.actions.new'),
                    'class' => 'btn btn-primary',
                    'role' => 'button',
                ],
            ],
        ]);

        $this->columnBuilder
            ->add('name', 'column', [
                'title' => 'Name',
            ])
            ->add(null, 'action', [
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => [
                    [
                        'route' => 'admin_group_edit',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'label' => '',
                        'icon' => 'fa fa-pencil',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_group_datatable';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAjaxUrl()
    {
        return $this->router->generate('admin_group_results');
    }

}
