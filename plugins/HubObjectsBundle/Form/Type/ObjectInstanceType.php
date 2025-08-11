<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\FormButtonsType;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ObjectInstanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ObjectDefinition $definition */
        $definition = $options['definition'];

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($definition) {
                $form = $event->getForm();

                foreach ($definition->getFields() as $field) {
                    $form->add(
                        $field->getName(),
                        $this->getFormTypeClass($field->getType()),
                        [
                            'label' => $field->getName(),
                            'required' => false, // Add logic for required fields later
                            'attr' => ['class' => 'form-control'],
                            'mapped' => false, // Data will be mapped manually to the JSON field
                        ]
                    );
                }
            }
        );

        $builder->add('buttons', FormButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('definition');
        $resolver->setAllowedTypes('definition', ObjectDefinition::class);
    }

    private function getFormTypeClass(string $fieldType): string
    {
        return match ($fieldType) {
            'textarea' => TextareaType::class,
            'number' => NumberType::class,
            'date' => DateType::class,
            'datetime' => DateTimeType::class,
            'boolean' => CheckboxType::class,
            default => TextType::class,
        };
    }
}
