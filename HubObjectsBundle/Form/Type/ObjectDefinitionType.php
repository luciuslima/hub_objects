<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\FormButtonsType;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectDefinitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'mautic.hubobjects.definition.name',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('pluralName', TextType::class, [
            'label' => 'mautic.hubobjects.definition.plural_name',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('slug', TextType::class, [
            'label' => 'mautic.hubobjects.definition.slug',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('fields', CollectionType::class, [
            'entry_type'    => FieldDefinitionType::class,
            'allow_add'     => true,
            'allow_delete'  => true,
            'by_reference'  => false,
        ]);

        $builder->add('buttons', FormButtonsType::class);

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObjectDefinition::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'hubobjects_definition';
    }
}
