<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Form\Type;

use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class CampaignConditionType extends AbstractType
{
    private ObjectDefinitionModel $definitionModel;

    public function __construct(ObjectDefinitionModel $definitionModel)
    {
        $this->definitionModel = $definitionModel;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $definitions = $this->definitionModel->getRepository()->findAll();
        $choices = [];
        foreach ($definitions as $definition) {
            $choices[$definition->getName()] = $definition->getId();
        }

        $builder->add('object', ChoiceType::class, [
            'choices'     => $choices,
            'label'       => 'mautic.hubobjects.campaign.object',
            'placeholder' => 'mautic.core.form.chooseone',
            'required'    => true,
        ]);

        $formModifier = function (FormInterface $form, ?int $definitionId = null) {
            if (!$definitionId) {
                return;
            }

            $definition = $this->definitionModel->getEntity($definitionId);
            $fieldChoices = [];
            foreach ($definition->getFields() as $field) {
                $fieldChoices[$field->getName()] = $field->getName();
            }

            $form->add('field', ChoiceType::class, [
                'choices'     => $fieldChoices,
                'label'       => 'mautic.hubobjects.campaign.field',
                'placeholder' => 'mautic.core.form.chooseone',
                'required'    => true,
            ]);

            $form->add('operator', ChoiceType::class, [
                'choices' => [
                    'Equal To' => '=',
                    'Not Equal To' => '!=',
                    'Greater Than' => '>',
                    'Less Than' => '<',
                    'Contains' => 'like',
                ],
                'label'    => 'mautic.hubobjects.campaign.operator',
                'required' => true,
            ]);

            $form->add('value', TextType::class, [
                'label'    => 'mautic.hubobjects.campaign.value',
                'required' => true,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $definitionId = $data['object'] ?? null;
                $formModifier($event->getForm(), $definitionId);
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $definitionId = $data['object'] ?? null;
                $formModifier($event->getForm(), (int) $definitionId);
            }
        );
    }
}
