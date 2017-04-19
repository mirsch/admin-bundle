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

use AdamQuaile\Bundle\FieldsetBundle\Form\FieldsetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminGroupType extends AbstractDataClassAwareType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('general_group', FieldsetType::class, [
            'legend' => 'mirsch.admin.form.legend.general',
            'attr' => [
                'class' => 'box-primary',
            ],
            'fields' => function (FormBuilderInterface $builder) {
                $builder
                    ->add('name', TextType::class, [
                        'label' => 'mirsch.admin.form.name',
                    ]);
            },
        ]);
        $this->buildRolesFields($builder);
    }

    /**
     * add roles selection to the form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildRolesFields(FormBuilderInterface $builder)
    {
        $builder->add('roles_group', FieldsetType::class, [
            'legend' => 'mirsch.admin.form.legend.roles',
            'attr' => [
                'class' => 'box-danger',
            ],
            'fields' => function (FormBuilderInterface $builder) {
                $builder->add('roles', RolesType::class, [
                    'label' => false,
                ]);
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_bundle_admin_group';
    }

}
