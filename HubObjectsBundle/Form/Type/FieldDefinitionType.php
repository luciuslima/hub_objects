<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Form\Type;

use MauticPlugin\HubObjectsBundle\Entity\FieldDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldDefinitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'mautic.hubobjects.field.name',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('type', ChoiceType::class, [
            'label' => 'mautic.hubobjects.field.type',
            'choices' => [
                'Text' => 'text',
                'Textarea' => 'textarea',
                'Number' => 'number',
                'Date' => 'date',
                'Datetime' => 'datetime',
                'Boolean' => 'boolean',
            ],
            'attr'  => ['class' => 'form-control'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FieldDefinition::class,
        ]);
    }
}
