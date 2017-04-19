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

class AdminUserDatatable extends AbstractDatatable
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = [])
    {
        $this->addTopActions([
            [
                'route' => $this->router->generate('admin_user_new'),
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
            ->add('username', 'column', [
                'title' => 'Username',
            ])
            ->add('email', 'column', [
                'title' => 'Email',
            ])
            ->add('lastName', 'column', [
                'title' => 'LastName',
            ])
            ->add('firstName', 'column', [
                'title' => 'FirstName',
            ])
            ->add('active', 'boolean', [
                'title' => 'Active',
                'true_icon' => 'fa fa-check-square-o',
                'false_icon' => 'fa fa-square-o',
                'filter' => [
                    'select',
                    [
                        'search_type' => 'eq',
                        'select_options' => ['' => 'Any', '1' => 'Yes', '0' => 'No'],
                    ],
                ],
            ])
            ->add(null, 'action', [
                'title' => $this->translator->trans('datatables.actions.title'),
                'actions' => [
                    [
                        'route' => 'admin_user_edit',
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
                    [
                        'route' => 'homepage',
                        'route_parameters' => [
                            '_switch_user' => 'username',
                        ],
                        'label' => '',
                        'icon' => 'fa fa-sign-in',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.impersonate'),
                            'class' => 'btn btn-warning btn-xs',
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
        return 'admin_user_datatable';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAjaxUrl()
    {
        return $this->router->generate('admin_user_results');
    }

}
