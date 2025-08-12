<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\FormButtonsType;
use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\HubObjectsBundle\Entity\Opportunity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpportunityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'mautic.hubobjects.opportunity.name',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('amount', MoneyType::class, [
            'label'    => 'mautic.hubobjects.opportunity.amount',
            'attr'     => ['class' => 'form-control'],
            'currency' => 'USD',
            'required' => false,
        ]);

        $builder->add('stage', ChoiceType::class, [
            'label' => 'mautic.hubobjects.opportunity.stage',
            'choices' => [
                'Prospecting' => 'prospecting',
                'Qualification' => 'qualification',
                'Proposal' => 'proposal',
                'Negotiation' => 'negotiation',
                'Closed Won' => 'closed_won',
                'Closed Lost' => 'closed_lost',
            ],
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ]);

        $builder->add('closeDate', DateTimeType::class, [
            'label' => 'mautic.hubobjects.opportunity.close_date',
            'widget' => 'single_text',
            'attr'  => ['class' => 'form-control'],
            'required' => false,
        ]);

        $builder->add('contact', EntityType::class, [
            'class' => Lead::class,
            'choice_label' => 'email',
            'label' => 'mautic.hubobjects.opportunity.contact',
            'attr' => ['class' => 'form-control'],
        ]);

        $builder->add('buttons', FormButtonsType::class);

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opportunity::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'hubobjects_opportunity';
    }
}
