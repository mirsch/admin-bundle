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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class AdminUserType extends AbstractDataClassAwareType
{

    /**
     * @var string
     */
    protected $groupEntityName;

    /**
     * @param string $dataClassName
     * @param string $groupEntityName
     */
    public function __construct($dataClassName, $groupEntityName)
    {
        parent::__construct($dataClassName);
        $this->groupEntityName = $groupEntityName;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildPersonalFields($builder);
        $this->buildEnableFields($builder);
        $this->buildPasswordFields($builder);
        $this->buildGroupsFields($builder);
        $this->buildRolesFields($builder);
    }

    /**
     * add personal fields like email or name to the form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildPersonalFields(FormBuilderInterface $builder)
    {
        $builder->add('personal_group', FieldsetType::class, [
            'legend' => 'mirsch.admin.form.legend.personal',
            'attr' => [
                'class' => 'box-primary',
            ],
            'fields' => function (FormBuilderInterface $builder) {
                $builder
                    ->add('username', TextType::class, [
                        'required' => true,
                        'label' => 'mirsch.admin.form.username',
                    ])
                    ->add('email', TextType::class, [
                        'required' => true,
                        'label' => 'mirsch.admin.form.email',
                    ])
                    ->add('firstName', TextType::class, [
                        'required' => false,
                        'label' => 'mirsch.admin.form.first_name',
                    ])
                    ->add('lastName', TextType::class, [
                        'required' => false,
                        'label' => 'mirsch.admin.form.last_name',
                    ]);
            },
        ]);
    }

    /**
     * add enable fields like 'isActive' to the form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildEnableFields(FormBuilderInterface $builder)
    {
        $builder->add('enable_group', FieldsetType::class, [
            'legend' => 'mirsch.admin.form.legend.enable',
            'attr' => [
                'class' => 'box-primary',
            ],
            'fields' => function (FormBuilderInterface $builder) {
                $builder->add('isActive', CheckboxType::class, [
                    'required' => false,
                    'label' => 'mirsch.admin.form.is_active',
                ]);
            },
        ]);
    }

    /**
     * add password and password repeat fields to the form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildPasswordFields(FormBuilderInterface $builder)
    {
        $builder->add('password_group', FieldsetType::class, [
            'legend' => 'mirsch.admin.form.legend.password',
            'attr' => [
                'class' => 'box-warning',
            ],
            'fields' => function (FormBuilderInterface $builder) {
                $builder->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'mirsch.form.errors.passwords_must_match',
                    'required' => false,
                    'first_options' => ['label' => 'mirsch.admin.form.password'],
                    'second_options' => ['label' => 'mirsch.admin.form.password_repeat'],
                ]);
            },
        ]);
    }

    /**
     * add groups select to the form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildGroupsFields(FormBuilderInterface $builder)
    {
        $builder->add('groups_group', FieldsetType::class, [
            'legend' => 'mirsch.admin.form.legend.groups',
            'attr' => [
                'class' => 'box-danger',
            ],
            'fields' => function (FormBuilderInterface $builder) {
                $builder->add('groups', Select2EntityType::class, [
                    'label' => 'mirsch.admin.form.groups',
                    'multiple' => true,
                    'remote_route' => 'admin_user_group_select',
                    'class' => $this->groupEntityName,
                    'primary_key' => 'id',
                    'text_property' => 'name',
                    'allow_clear' => true,
                ]);
            },
        ]);
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
                $builder->add('userRoles', RolesType::class, [
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
        return 'admin_bundle_admin_user';
    }

}
